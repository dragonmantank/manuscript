<?php

class Admin_GroupsController extends Zend_Controller_Action
{
    public function addAction()
    {
        $form = new Admin_Form_AddGroup();

        if($this->_request->isPost()) {
            if($form->isValid($this->_request->getPost())) {
                $groups = new Application_Model_Groups();
                $data   = $form->getValues();

                try {
                    $groups->add(array('name' => $data['addGroup']));
                    $this->_helper->redirector('index');
                } catch(Exception $e) {
                    $this->view->message = $e->getMessage();
                }
            } else {
                $this->view->message = "There was a problem with your submission";
            }
        }

        $this->view->form = $form;

    }

    public function indexAction()
    {
        $groups = new Application_Model_Groups();
        $list   = $groups->fetchAll();
        
        $this->view->list = $list;
    }
}
