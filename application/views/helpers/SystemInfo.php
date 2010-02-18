<?php

class Zend_View_Helper_SystemInfo
{
    public function systemInfo($switch)
    {
        switch($switch) {
            case 'availableSpace':
                return disk_free_space('.');
                break;
            case 'numFiles':
                $files  = new Application_Model_Files();
                return count($files->fetchAll());
                break;
            case 'totalSpace':
                return disk_total_space('.');
                break;
            case 'usedSpace':
                return disk_total_space('.') - disk_free_space('.');
                break;
            default:
                return 'Not Defined';
                break;
        }
    }
}