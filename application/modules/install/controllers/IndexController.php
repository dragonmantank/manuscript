<?php

class Install_IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout->setLayout('install-layout');
        $config = $this->getInvokeArg('bootstrap')->getOptions();

        if(@$config['installed']) {
            $this->_helper->redirector('index', 'index', 'index');
        }
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

                $this->_installTables($config, $db);

                $this->_helper->redirector('index', 'index', 'index');
            }
        }

        $this->view->form   = $form;

	if(stripos($_SERVER['SERVER_SOFTWARE'], 'Microsoft') === false) {
		$this->render('index-linux');
	}
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

        if($data['dbType'] == 'sqlserver2k8') {
            $config->resources->db->adapter             = 'SQLSRV';
            $config->resources->db->params->host        = $data['host'];
            $config->resources->db->params->username    = $data['username'];
            $config->resources->db->params->password    = $data['password'];
            $config->resources->db->params->dbname      = $data['dbname'];
            $config->resources->db->params->driver_options = array();
            $config->resources->db->params->driver_options->ReturnDatesAsStrings = true;
        }

        return $config;
    }

    /**
     * Calls the schema manager and installs the tables
     * @param Zend_Config $config
     * @param Zend_Db_Adapter $db
     */
    protected function _installTables($config, $db)
    {
        if(is_file($config->resources->db->params->dbname)) {
            unlink($config->resources->db->params->dbname);
        }

        $base = Tws_SchemaManager::factory($db);
        $base->setNamespace('Manuscript_SchemaManager');
        $base->install();
    }
}
