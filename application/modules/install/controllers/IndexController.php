<?php

class Install_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('install-layout');
    }
    
    public function indexAction()
    {
        if($this->_request->isPost()) {
            $config = new Zend_Config(array(), true);
            $config->installed  = true;
            $config->resources = array();
            $config->resources->db  = array();
            $config->resources->db->adapter = 'PDO_SQLITE';
            $config->resources->db->params  = array();
            $config->resources->db->params->dbname  = APPLICATION_PATH.'/../data/db/manuscript.db';

            $writer = new Zend_Config_Writer_Ini();
            $writer->write(APPLICATION_PATH.'/configs/local.ini', $config);
        }
    }

    public function writeconfigAction()
    {

    }
}