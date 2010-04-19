<?php

class Sqlite extends Tws_SchemaManager_Changeset_Abstract
{
    public function upgrade()
    {
        $path = dirname(__FILE__);
        $schema = file_get_contents($path.'/sqlite-schema.sql');
        $this->_db->getConnection()->exec($schema);
        
        $data = file_get_contents($path.'/sqlite-data.sql');
        $this->_db->getConnection()->exec($data);
    }
}
