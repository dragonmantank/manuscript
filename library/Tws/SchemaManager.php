<?php

class Tws_SchemaManager
{
    static public function factory(Zend_Db_Adapter_Abstract $db)
    {
        switch(get_class($db)) {
            case 'Zend_Db_Adapter_Pdo_Sqlite':
                return new Tws_SchemaManager_Sqlite($db);
                break;
            default:
                throw new Exception('Unknown database adapter for Schema Manager.');
                break;
        }
    }
}
