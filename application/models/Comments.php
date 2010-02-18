<?php

class Application_Model_Comments
{
    protected $_dbTable;

    public function add(array $commentData)
    {
        $commentData['dateAdded'] = date('Y-m-d h:i:s');

        return $this->getDbTable()->insert($commentData);
    }

    public function fetchAll($where = null, $order = null, $count = null, $offset = null)
    {
        $select = $this->getDbTable()->select()->from(array('c' => 'comments'))
                                               ->join(array('f' => 'files'), 'c.fileId = f.id', array('title'))
                                               ->join(array('d' => 'files_detail'), 'f.detailId = d.id', array('fsFilename'))
                                               ->setIntegrityCheck(false);

        if($order != null) {
            $select->order($order);
        }

        if($count != null) {
            $select->limit($count);
        }
        
        return $this->getDbTable()->fetchAll($select);
    }

    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Comments');
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
            throw new Exception('Not a valid table gateway for Comments Model');
        }
    }
}