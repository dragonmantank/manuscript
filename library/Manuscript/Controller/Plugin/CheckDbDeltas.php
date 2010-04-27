<?php

class Manuscript_Controller_Plugin_CheckDBDeltas extends Zend_Controller_Plugin_Abstract
{
    protected $_version;
    protected $_db;

    public function __construct($version, $db)
    {
        $this->_version = $version;
        $this->_db      = $db;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $select = $this->_db->select()->from('config', array('value'))->where('key_name = ?', 'version');
        $result = $this->_db->fetchCol($select);

        if((int)$this->_version !== (int)$result[0]) {
            $request->setModuleName('upgrade')
                    ->setControllerName('index')
                    ->setActionName('index')
                    ->setDispatched(true);

        }
    }
}
