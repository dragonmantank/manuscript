<?php

class Sqlsrv extends Tws_SchemaManager_Changeset_Abstract
{
    public function upgrade()
    {
        $conn = $this->_db->getConnection();
        $path = dirname(__FILE__);

        $schema = file_get_contents($path.'/sqlsrv-schema.sql');
        $mimetypes = file_get_contents($path.'/sqlsrv-schema-mimetypes.sql');
        $data = file_get_contents($path.'/sqlsrv-data.sql');
    
        if(!sqlsrv_query($conn, $schema)) {
            echo "Unable to create tables";
            print_r(sqlsrv_errors());
            die();
        }

        if(!sqlsrv_query($conn, $mimetypes)) {
            echo "Unable to create tables - mimetypes";
            print_r(sqlsrv_errors());
            die();
        }

        if(!sqlsrv_query($conn, $data)) {
            echo "Unable to seed data to tables";
            print_r(sqlsrv_errors());
            die();
        }
    }
}
