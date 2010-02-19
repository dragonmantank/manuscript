<?php

class Zend_View_Helper_GetFileComments
{
    public function getFileComments($fileId)
    {
        $comments   = new Application_Model_Comments();

        return $comments->getDbTable()->fetchAll($comments->getDbTable()->select()->where('fileId = ?', $fileId), 'id DESC');
    }
}