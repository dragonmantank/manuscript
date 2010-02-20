<?php

class Admin_MimetypesController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->layout->setLayout('admin-layout');
    }

    public function indexAction()
    {
        $form   = new Admin_Form_AddMimetype();

        if($this->_request->isPost()) {
            $data   = $this->_request->getPost();

            if($form->isValid($data)) {
                $data   = $form->getValues();
                $mimetypes  = new Application_Model_Mimetypes();

                try {
                    $mimetypes->add($data);
                    $this->_helper->redirector('index');
                } catch(Exception $e) {
                    $message    = $e->getMessage();
                }
            } else {
                $this->message  = 'There was a problem with the form submission';
            }
        }

        $this->view->form   = $form;
    }
}
