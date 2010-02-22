<?php

class Accelerators_InstallController extends Zend_Controller_Action
{
    public function init()
    {
        $cs = $this->_helper->getHelper('contextSwitch');
        $cs->addActionContext('create', 'xml')
           ->initContext();
    }

    public function createAction()
    {
        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
    }
}