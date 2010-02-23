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

    public function changestatusAction()
    {
        $users  = new Application_Model_Users();
        $users->changeStatus($this->_request->getParam('user'));
        $this->_helper->redirector('index');
    }

    public function editAction()
    {
        $users  = new Application_Model_Users();
        $user   = $users->find($this->_request->getParam('user'));
        $form   = new Admin_Form_EditUser();
        
        if($this->_request->isPost()) {
            $data   = $this->_request->getPost();
            if($form->isValid($data)) {
                try {
                    $users->update($form->getValues(), $user->id);
                    $this->_helper->redirector('index');
                } catch (Exception $e) {
                    $this->view->message    = 'Error saving user info: '.$e->getMessage();
                }
            } else {
                $this->view->message    = 'There was a problem with the form entry';
            }
        } else {
            $form->populate($user->toArray());
            $form->getElement('password')->setValue('');
        }

        $this->view->user   = $user;
        $this->view->form   = $form;
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