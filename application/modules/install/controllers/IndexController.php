<?php

class Install_IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout->setLayout('install-layout');
        $config = $this->getInvokeArg('bootstrap')->getOptions();

        if($config['installed']) {
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

                if($data['dbType'] == 'sqlite') {
                    $this->_buildSqlite($config, $db);
                }

                if($data['dbType'] == 'sqlserver2k8') {
                    $this->_buildSqlServer($config, $db);
                }

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

    /**
     *
     * @param Zend_Config $config
     * @param Zend_Db_Adapter $db
     */
    protected function _buildSqlServer($config, $db)
    {
        $conn = $db->getConnection();

        $schemaSql = file_get_contents(APPLICATION_PATH.'/../scripts//schema.sqlserv2008.sql');
        $schemaViewSql = file_get_contents(APPLICATION_PATH.'/../scripts//schema-new_mimetypes.sqlserv2008.sql');
        if(!sqlsrv_query($conn, $schemaSql)) {
            echo "Unable to create tables<br/>";
            print_r(sqlsrv_errors());
            die();
        }
        sqlsrv_query($conn, $schemaViewSql);

        $dataSql = file_get_contents(APPLICATION_PATH.'/../scripts//data.sqlserv2008.sql');
        sqlsrv_query($conn, $dataSql);
    }
}
