<?php

class Install_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('install-layout');
    }
    
    public function indexAction()
    {
        $form   = new Install_Form_Install();

        if($this->_request->isPost()) {
            $data   = $this->_request->getPost();

            if($form->isValid($data)) {
                $data   = $form->getValues();

                $config = $this->_buildConfig($data);

                $writer = new Zend_Config_Writer_Ini();
                $writer->write(APPLICATION_PATH.'/configs/local.ini', $config);

                $db = Zend_Db::factory($config->resources->db->adapter, $config->resources->db->params);

                if($data['dbType'] == 'sqlite') {
                    $this->_buildSqlite($config, $db);
                }

                $this->_helper->redirector('index', 'index', 'index');
            }
        }

        $this->view->form   = $form;
    }

    protected function _buildConfig($data)
    {
        $config = new Zend_Config(array(), true);
        $config->installed  = true;
        $config->resources = array();
        $config->resources->db  = array();
        $config->resources->db->params  = array();

        if($data['dbType'] == 'sqlite') {
            $config->resources->db->adapter = 'PDO_SQLITE';
            $config->resources->db->params->dbname  = realpath(APPLICATION_PATH.'/../data/db').'/manuscript.db';
        }

        return $config;
    }

    protected function _buildSqlite($config, $db)
    {
        if(is_file($config->resources->db->params->dbname)) {
            unlink($config->resources->db->params->dbname);
        }

        $schemaSql = file_get_contents(APPLICATION_PATH.'/../scripts//schema.sqlite.sql');
        $db->getConnection()->exec($schemaSql);
        $dataSql = file_get_contents(APPLICATION_PATH.'/../scripts//data.sqlite.sql');
        $db->getConnection()->exec($dataSql);
    }
}
