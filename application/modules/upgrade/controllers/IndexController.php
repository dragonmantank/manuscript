<?php

class Upgrade_IndexController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->layout->setLayout('install-layout');
    }

    public function indexAction()
    {
        if($this->_request->isPost()) {
            $db = $this->getInvokeArg('bootstrap')->getResource('db');
            $config = $this->getInvokeArg('bootstrap')->getOptions();
            $manager = Tws_SchemaManager::factory($db);
            $manager->setNamespace('Manuscript_SchemaManager');
            $manager->upgrade($config['delta_version']);
        }
    }
}
