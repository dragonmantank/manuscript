<?php

class Manuscript_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $auth   = Zend_Auth::getInstance();

        $openModules    = array('auth', 'accelerators');

        if(!in_array($module, $openModules)) {
            if( !$auth->hasIdentity()) {
                $request->setModuleName('auth')
                        ->setControllerName('index')
                        ->setActionName('index')
                        ->setDispatched(true);
            }
        }
    }
}