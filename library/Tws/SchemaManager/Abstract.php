<?php

abstract class Tws_SchemaManager_Abstract
{
    protected $_db;
    protected $_namespace;
    protected $_driver;

    public function __construct(Zend_Db_Adapter_Abstract $db)
    {
        $this->_db = $db;
    }

    public function getCurrentVersion()
    {
        $select = $this->_db->select()->from('config', array('value'))->where('key = ?', 'version');
        $result = $this->_db->fetchCol($select);

        return $result[0];
    }

    public function getDriver()
    {
        if($this->_driver == null) {
            throw new Exception('Driver has not been set for Schema Manager');
        }

        return $this->_driver;
    }

    public function getNamespace()
    {
        if( $this->_namespace == null ) {
            throw new Exception('Namespace has not been set for Schema Manager');
        }

        return $this->_namespace;
    }

    public function install()
    {
        $path = realpath(APPLICATION_PATH.'/../library/'.str_replace('_', '/', $this->getNamespace()).'/Base').'/'.$this->getDriver().'.php';
        require_once($path);
        $class = $this->getDriver();
        $installer = new $class($this->_db);
        $installer->upgrade();
    }

    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }

    protected function _split($path)
    {
        $file = end(explode('/', $path));
        preg_match('/^([0-9]+)\-(.*)\.php/', $file, $matches);
        
        return $matches;
    }

    public function upgrade($version = null)
    {
        $currentVersion = $this->getCurrentVersion();
        $path = realpath(APPLICATION_PATH.'/../library/'.str_replace('_', '/', $this->getNamespace()).'/Delta/'.$this->getDriver());
        $deltas = glob($path.'/*.php');
        
        if(count($deltas)) {
            if($version == null) {
                list(, $version,) = $this->_split(end($deltas));
                reset($deltas);
            }

            if($version > $currentVersion) {
                foreach($deltas as $file) {
                    $data = $this->_split($file);
                    require_once($file);
                    $changeset = new $data[2]($this->_db);
                    $changeset->upgrade();
                }
            }
        } else {
            throw new Exception('There are no deltas available!');
        }
    }
}
