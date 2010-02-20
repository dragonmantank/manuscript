<?php

class Zend_View_Helper_MimeType
{
    public function MimeType($mimetype)
    {
        $mimetypes  = new Application_Model_Mimetypes();
        $desc       = $mimetypes->fetchDescription($mimetype);

        if(count($desc)) {
            return $desc->description;
        } else {
            return $mimetype;
        }
    }
}

