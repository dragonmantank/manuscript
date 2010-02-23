<?php

class Application_Model_Users
{
    protected $_dbTable;

    public function add($data)
    {
        $data['password']       = hash('sha384', ($data['password'] + $data['username']));
        $data['primaryGroup']   = (int)$data['primaryGroup'];
        $data['username']       = strtolower($data['username']);

        return $this->getDbTable()->insert($data);
    }

    public function changeStatus($id)
    {
        $user       = $this->find($id);
        $newStatus  = ($user->active ? 0 : 1);

        return $this->getDbTable()->update(array('active' => $newStatus), 'id = '.$id);
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
            $select = $this->getDbTable()->select()->where('username LIKE ?', $fileId);
            $file = $this->getDbTable()->fetchRow($select);
        }

        return $file;
    }

    public function getDbTable()
    {
        if($this->_dbTable === null) {
            $this->setDbTable('Application_Model_DbTable_Users');
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
            throw new Exception('Not a valid table gateway for Users Model');
        }
    }

    public function update($data, $id)
    {
        $new['username']        = $data['username'];
        $new['name']            = $data['name'];
        $new['email']           = $data['email'];
        $new['primaryGroup']    = $data['primaryGroup'];

        if(!empty($data['password'])) {
            $new['password']    = hash('sha384', $data['password'] + $data['username']);
        }

        return $this->getDbTable()->update($new, 'id = '.$id);


    }
}
