<?php

class Application_Model_Files
{
    protected $_dbTable;

    public function add($fileData, array $tags)
    {
        $tmpName    = $fileData['tmp_name'];
        unset($fileData['tmp_name']);

        if( is_uploaded_file($tmpName) ) {
            $extraData  = array(
                'directory'     => 'documents',
                'reference'     => md5($fileData['originalFilename']),
                'dateUploaded'  => date('Y-m-d h:i:s'),
                'version'       => 1,
            );

            $fileData   = array_merge($fileData, $extraData);
       
            try {
                $id = $this->getDbTable()->insert($fileData);
                $newName    = realpath(APPLICATION_PATH.'/../data/'.$fileData['directory']).'/'.$fileData['reference'];
                if(move_uploaded_file($tmpName, $newName)) {
                    $tagsModel   = new Application_Model_Tags();
                    $tagsModel->associate($tags, $id);

                    return $id;
                } else {
                    $file = $this->getDbTable()->find($id)->current();
                    $file->delete();

                    throw new Exception('Could not move file to storage. Removed from DB');
                }
            } catch (Exception $e) {
                throw new Exception('Error occured while adding the file: '.$e->getMessage());
            }
        } else {
            throw new Exception('File was not uploaded!');
        }
    }

    public function addComment(array $commentData)
    {
        $comments   = new Application_Model_Comments();
        return $comments->add($commentData);
    }

    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->getDbTable()->fetchAll($where, $order, $count, $offset);
    }

    public function find($fileId)
    {
        if( is_numeric($fileId) ) {
            $result = $this->getDbTable()->find($fileId);
            $file = $result->current();
        } else {
            $select = $this->getDbTable()->select()->where('reference LIKE ?', $fileId);
            $file = $this->getDbTable()->fetchRow($select);
        }

        return $file;
    }

    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Files');
        }

        return $this->_dbTable;
    }

    public function search($query)
    {
        $select = $this->getDbTable()->select()->where('originalFilename LIKE ?', '%'.$query.'%')
                                               ->orWhere('title LIKE ?', '%'.$query.'%');

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
}
