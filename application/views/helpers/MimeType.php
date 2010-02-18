<?php

class Zend_View_Helper_MimeType
{
        public function MimeType($mimetype)
        {
                switch($mimetype) {
                        case 'application/octet-stream':
                                $human  = 'Executable Program';
                                break;
                        case 'application/x-javascript':
                                $human  = 'JavaScript File';
                                break;
                        case 'application/x-msdos-program':
                                $human  = 'MS-DOS Program';
                                break;
                        case 'application/zip':
                                $human  = 'Zip File';
                                break;
                        case 'image/bmp':
                                $human  = 'Bitmap Image';
                                break;
                        case 'text/plain';
                                $human  = 'Plaintext File';
                                break;
                        case 'text/xml':
                                $human  = 'XML File';
                                break;
                        default:
                                $human  = $mimetype;
                                break;
                }

                return $human;
        }
}

