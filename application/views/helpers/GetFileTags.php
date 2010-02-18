<?php

class Zend_View_Helper_GetFileTags
{
    public function getFileTags($fileId)
    {
        $tagsModel  = new Application_Model_Tags();
        $tags       = $tagsModel->fetchTags($fileId);

        return $tags;
    }
}