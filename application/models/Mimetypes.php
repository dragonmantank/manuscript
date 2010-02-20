<?php

class Application_Model_Mimetypes
{
    protected $_dbTable;
    protected $_newMimetypesTable;

    public function add($data)
    {
        $d['mimetype']      = $data['newMimetype'];
        $d['description']   = $data['description'];
        
        return $this->getDbTable()->insert($d);
    }
    public function fetchDescription($mimetype)
    {
        return $this->getDbTable()->fetchDescription($mimetype);
    }

    public function fetchNew()
    {
        $result = $this->getNewMimetypesTable()->fetchAll();
        $mimetypes  = array();
        foreach($result as $row) {
            $mimetypes[$row->newMimetype] = $row->newMimetype;
        }
        return $mimetypes;
    }

    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Mimetypes');
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
            throw new Exception('Not a valid table gateway for Mimetypes Model');
        }
    }

    public function getNewMimetypesTable()
    {
        if($this->_newMimetypesTable === null) {
            $this->setNewMimetypesTable('Application_Model_DbTable_NewMimetypes');
        }

        return $this->_newMimetypesTable;
    }

    public function setNewMimetypesTable($table)
    {
        if(is_string($table)) {
            $this->_newMimetypesTable = new $table();
        } elseif($table instanceof Zend_Db_Table_Abstract) {
            $this->_newMimetypesTable = $table;
        } else {
            throw new Exception('Not a valid table gateway for New Mimetypes Model');
        }
    }
}
