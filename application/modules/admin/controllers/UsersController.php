<?php

class Admin_UsersController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('admin-layout');
    }

    public function indexAction()
    {
        $users  = new Application_Model_Users();

        $this->view->users  = $users->fetchAll();
    }
}