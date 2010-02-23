<?php

class Application_Model_Tags
{
    protected $_dbTable;
    protected $_xrefTable;

    public function add($tagName)
    {
        $tagName = trim($tagName);
        if(!empty($tagName)) {
            $select = $this->getDbTable()->select()->where('name LIKE ?', $tagName);
            $result = $this->getDbTable()->fetchAll($select);

            if(count($result)) {
                return $result[0]->id;
            } else {
                return $this->getDbTable()->insert(array('name' => $tagName));
            }
        }

        return null;
    }

    public function associate(array $tags, $id)
    {
        $this->disassociate($id);
        foreach($tags as $tag) {
            $tagId  = $this->add($tag);
            if($tagId !== null) {
                $this->getXrefTable()->insert(array('tagId' => $tagId, 'fileId' => $id));
            }
        }
    }

    public function disassociate($fileId)
    {
        return $this->getXrefTable()->delete('fileId = '.$fileId);
    }

    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->getDbTable()->fetchAll($where, $order, $count, $offset);
    }

    public function fetchFiles($tagId)
    {
        $select = $this->getXrefTable()->select()->from(array('x' => 'files_tags_xref'))
                                       ->join(array('f' => 'files'), 'f.id = x.fileId', array('title', 'revision'))
                                       ->join(array('d' => 'files_detail'), 'f.detailId = d.id', array('mimetype', 'dateUploaded', 'fsFilename'))
                                       ->where('x.tagId = ?', $tagId)
                                       ->setIntegrityCheck(false);

        return $this->getXrefTable()->fetchAll($select);
    }

    public function fetchTags($fileId)
    {
        $select = $this->getXrefTable()->select()->from(array('x' => 'files_tags_xref'))
                                       ->join(array('t' => 'tags'), 't.id = x.tagId', array('name'))
                                       ->where('x.fileId = ?', $fileId)
                                       ->setIntegrityCheck(false);

        return $this->getXrefTable()->fetchAll($select);
    }

    public function find($id)
    {
        if( is_numeric($id) ) {
            $result = $this->getDbTable()->find($id);
            $file = $result->current();
        } else {
            $select = $this->getDbTable()->select()->where('name LIKE ?', $id);
            $file = $this->getDbTable()->fetchRow($select);
        }

        return $file;
    }

    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Tags');
        }

        return $this->_dbTable;
    }
    
    public function setDbTable($table)
    {
        if(is_string($table)) {
            $this->_dbTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_dbTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Tags Model');
        }
    }

    public function getXrefTable()
    {
        if($this->_xrefTable === null) {
            $this->setXrefTable('Application_Model_DbTable_FilesTagsXref');
        }

        return $this->_xrefTable;
    }

    public function remove($tagId)
    {
        $this->getDbTable()->delete('id = '.$tagId);
        $this->getXrefTable()->delete('tagId = '.$tagId);
    }

    public function rename($tagId, $name)
    {
        $where  = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $tagId);
        $this->getDbTable()->update(array('name' => $name), $where);
    }

    public function setXrefTable($table)
    {
        if(is_string($table)) {
            $this->_xrefTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_xrefTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for Files to Tags Xref Model');
        }
    }
}
