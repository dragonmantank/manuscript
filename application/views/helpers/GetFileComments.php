<?php

class Zend_View_Helper_GetFileComments
{
    public function getFileComments($fileId)
    {
        $comments   = new Application_Model_Comments();

        return $comments->fetchAll($comments->getDbTable()->select()->where('fileId = ?', $fileId), 'id DESC');
    }
}