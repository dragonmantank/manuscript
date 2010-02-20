<?php

class Application_Model_DbTable_Mimetypes extends Zend_Db_Table_Abstract
{
    protected $_name    = 'mimetypes';

    public function fetchDescription($mimetype)
    {
        $select = $this->select()->from($this->_name, array('description'));

        if(!is_numeric($mimetype)) {
            $select->where('mimetype LIKE ?', $mimetype);
        } else {
            $select->where('id = ?', $mimetype);
        }

        return $this->fetchRow($select);
    }
}
