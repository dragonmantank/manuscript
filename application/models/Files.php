<?php

class Application_Model_Files
{
    protected $_dbTable;
    protected $_detailTable;

    public function add($fileData, array $tags)
    {
        if( is_uploaded_file($fileData['tmp_name']) ) {
            // Gather the Header Data
            $hData  = $this->_buildHeaderData($fileData);
            
            // Begin gathering the Detail data
            $dData  = $this->_buildDetailData($fileData);

            try {
                $dData['fileId']    = $this->_writeHeaderData($hData);
                $detailId           = $this->_writeDetailData($dData);
                $this->_updateHeaderData(array('detailId' => $detailId), $dData['fileId']);

                $newName    = realpath(APPLICATION_PATH.'/../data/'.$hData['directory']).'/'.$dData['fsFilename'];
                if(move_uploaded_file($fileData['tmp_name'], $newName)) {
                    $tagsModel   = new Application_Model_Tags();
                    $tagsModel->associate($tags, $dData['fileId']);

                    return $dData['fileId'];
                } else {
                    $this->remove($dData['fileId']);

                    throw new Exception('Could not move file to storage. Removed from DB');
                }
            } catch (Exception $e) {
                throw new Exception('Error occured while adding the file: '.$e->getMessage());
            }
        } else {
            throw new Exception('File was not uploaded');
        }
    }

    public function addComment(array $commentData)
    {
        $comments   = new Application_Model_Comments();
        return $comments->add($commentData);
    }

    protected function _buildDetailData($fileData)
    {
        $dData['fsFilename']        = md5($fileData['filename'].$fileData['title'].time());
        $dData['mimetype']          = $fileData['mimetype'];
        $dData['size']              = $fileData['size'];
        $dData['dateUploaded']      = date('Y-m-d h:i:s');
        $dData['author']            = $fileData['originalAuthor'];

        return $dData;
    }

    protected function _buildHeaderData($fileData)
    {
        $hData['filename']          = $fileData['filename'];
        $hData['directory']         = 'documents';
        $hData['originalAuthor']    = $fileData['originalAuthor'];
        $hData['title']             = $fileData['title'];
        $hData['revision']          = 1;

        return $hData;
    }

    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->getDbTable()->fetchAll($where, $order, $count, $offset);
    }

    public function find($fileId)
    {
        $select = $this->getDbTable()->select()->from(array('h' => 'files'))
                                               ->join(array('d' => 'files_detail'), 'h.id = d.fileId', array('fsFilename', 'mimetype', 'size', 'dateUploaded', 'author'))
                                               ->setIntegrityCheck(false);
        if( is_numeric($fileId) ) {
            $select->where('h.id = ?', $fileId);
        } else {
            $select->where('d.fsFilename LIKE ?', $fileId);
        }

        return $this->getDbTable()->fetchRow($select);
    }

    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Files');
        }

        return $this->_dbTable;
    }

    public function getDetailTable()
    {
        if($this->_detailTable === null) {
            $this->setDetailTable('Application_Model_DbTable_FilesDetail');
        }

        return $this->_detailTable;
    }

    public function remove($id)
    {
        $header = $this->getDbTable()->find($file->id)->current();
        $this->getDetailTable()->find($header->detailId)->current()->delete();
        $header->delete();
    }

    public function search($query)
    {
        $select = $this->getDbTable()->select()->from(array('h' => 'files'))
                                               ->join(array('d' => 'files_detail'), 'h.id = d.fileId', array('fsFilename', 'mimetype', 'size', 'dateUploaded', 'author'))
                                               ->where('filename LIKE ?', '%'.$query.'%')
                                               ->orWhere('title LIKE ?', '%'.$query.'%')
                                               ->setIntegrityCheck(false);

        return $this->getDbTable()->fetchAll($select);
    }
    
    public function setDbTable($table)
    {
        if(is_string($table)) {
            $this->_dbTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_dbTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Filess Model');
        }
    }

    public function setDetailTable($table)
    {
        if(is_string($table)) {
            $this->_detailTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_detailTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Files Detail Model');
        }
    }

    protected function _updateHeaderData($data, $id)
    {
        return $this->getDbTable()->update($data, 'id = '.$id);
    }

    protected function _writeDetailData($dData)
    {
        return $this->getDetailTable()->insert($dData);
    }

    protected function _writeHeaderData($hData)
    {
        return $this->getDbTable()->insert($hData);
    }
}
