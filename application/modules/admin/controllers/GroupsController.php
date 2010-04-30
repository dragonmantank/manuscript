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
                    $groups->add(array('name' => $data['addGroup'], 'permissions' => $data['permission']));
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

    public function deleteAction()
    {
        $groups = new Application_Model_Groups();
        $group  = $groups->find($this->_request->getParam('id'));

        if($this->_request->isPost()) {
            if($this->_request->getParam('delete') !== null) {
                $group->delete();
            }

            $this->_helper->redirector('index');
        }

        $this->view->name   = $group->name;
    }
    
    public function editAction()
    {
        $form   = new Admin_Form_AddGroup();
        $groups = new Application_Model_Groups();
        $group  = $groups->find($this->_request->getParam('id'));
        $form->getElement('submit')->setLabel('Edit Group');

        if($this->_request->isPost()) {
            if($form->isValid($this->_request->getPost())) {
                $groups->update($this->_request->getParam('id'), array('name' => $form->getValue('addGroup'), 'permissions' => $form->getValue('permission')));
                $this->_helper->redirector('index');
            } else {
                echo 'Bad';
            }
        } else {
            $perms = $groups->fetchPermissionsList($group->id);
            $permissions = array();
            foreach($perms as $permission) {
                $permissions[] = $permission['id'];
            }
            $form->getElement('permission')->setValue($permissions);
            $form->getElement('addGroup')->setValue($group->name);
        }

        $this->view->form   = $form;
    }

    public function indexAction()
    {
        $groups = new Application_Model_Groups();
        $list   = $groups->fetchAll();
        
        $this->view->list = $list;
    }
}
