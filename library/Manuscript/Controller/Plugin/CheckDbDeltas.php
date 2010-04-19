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
        $select = $this->_db->select()->from('config')->where('key = ?', 'version');
        $result = $this->_db->fetchCol($select);

        if($this->_version !== $result[0]) {
            $request->setModuleName('upgrade')
                    ->setControllerName('index')
                    ->setActionName('index')
                    ->setDispatched(true);

        }
    }
}
