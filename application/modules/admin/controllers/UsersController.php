<?php

class Admin_UsersController extends Zend_Controller_Action
{
    public function addAction()
    {
        $form       = new Admin_Form_User();
        $message    = '';

        if($this->_request->isPost()) {
            $data   = $this->_request->getPost();

            if($form->isValid($data)) {
                $data   = $form->getValues();
                unset($data['confPassword']);

                $users  = new Application_Model_Users();

                try {
                    $users->add($data);
                    $this->_helper->redirector('index');
                } catch(Exception $e) {
                    $message = $e->getMessage();
                    $message = (preg_match('/not unique/', $message) ? 'Username or E-mail Address is already in use' : $e->getMessage);
                }
            } else {
                $message = 'There were problems with the entry. Please see below.';
            }
        }

        $this->view->message    = $message;
        $this->view->form       = $form;
    }

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