<?php

abstract class Tws_SchemaManager_Changeset_Abstract
{
    protected $_db;

    public function __construct(Zend_Db_Adapter_Abstract $db)
    {
        $this->_db  = $db;
    }

    abstract public function upgrade();
}
