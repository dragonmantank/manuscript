<?php

class Zend_View_Helper_GetTags
{
    public function getTags()
    {
        $tags   = new Application_Model_Tags();

        return $tags->fetchAll(null, 'name ASC');
    }
}