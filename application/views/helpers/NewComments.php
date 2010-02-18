<?php

class Zend_View_Helper_NewComments
{
    public function newComments()
    {
        $files   = new Application_Model_Comments();

        return $files->fetchAll(null, 'id DESC', 5);
    }
}