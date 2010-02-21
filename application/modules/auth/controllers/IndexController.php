<?php

class Auth_IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form       = new Auth_Form_Login();
        $message    = '';

        if($this->_request->isPost()) {
            if($form->isValid($this->_request->getPost())) {
                $username       = $form->getValue('username');
                $password       = md5($form->getValue('password'));

                $db =  $this->getInvokeArg('bootstrap')->getResource('db');
                $authAdapter = new Zend_Auth_Adapter_DbTable( $db );

                try {
                        $authAdapter->setTableName('user_accounts');
                        $authAdapter->setIdentityColumn('username');
                        $authAdapter->setCredentialColumn('password');
                        $authAdapter->setIdentity($username);
                        $authAdapter->setCredential($password);

                        $auth   = Zend_Auth::getInstance();
                        $result = $auth->authenticate($authAdapter);

                        if( $result->isValid() ) {
                                $auth->getStorage()->write( $authAdapter->getResultRowObject(null, 'password') );
                                $this->_helper->redirector('index', 'index', 'index');
                        } else {
                                $message        = 'An invalid username or password was entered.';
                        }
                } catch (Exception $e) {
                        $message        = $e->getMessage();
                }

            }
        }

        $this->view->message    = $message;
        $this->view->form   = $form;
    }

    public function logoutAction()
    {
        session_destroy();
        $auth   = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $this->_helper->redirector('index', 'index', 'auth');
    }
}