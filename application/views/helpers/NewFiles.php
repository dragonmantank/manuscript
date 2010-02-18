<?php

class Zend_View_Helper_NewFiles
{
    public function newFiles()
    {
        $files   = new Application_Model_Files();

        return $files->fetchAll(null, 'id DESC', 5);
    }
}