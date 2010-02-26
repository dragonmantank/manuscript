<?php

class Manuscript_Controller_Plugin_RunInstall extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $request->setModuleName('install')
                ->setControllerName('index')
                ->setActionName('index')
                ->setDispatched(true);
    }
}