<?php

class Admin_TagsController extends Zend_Controller_Action
{
    public function addAction()
    {
        if($this->_request->isPost()) {
            $form   = new Admin_Form_AddTag();
            $data   = $this->_request->getPost();

            if($form->isValid($data)) {
                $data   = $form->getValues();
                $tags   = new Application_Model_Tags();

                try {
                    $tags->add($data['addTag']);
                    $this->_helper->FlashMessenger->addMessage('Tag was added successfully');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage('An error occured: '.$e->getMessage());
                }
            }
        }

        $this->_helper->redirector('index');
    }

    public function deleteAction()
    {
        if($this->_request->isPost()) {
            $form   = new Admin_Form_DeleteTag();
            $data   = $this->_request->getPost();

            if($form->isValid($data)) {
                $data   = $form->getValues();
                $tags   = new Application_Model_Tags();

                try {
                    $tags->remove($data['deleteTags']);
                    $this->_helper->FlashMessenger->addMessage('Tag was deleted successfully');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage('An error occured: '.$e->getMessage());
                }
            } else {
                $this->_helper->FlashMessenger->addMessage('Unable to delete tag');
            }
        }

        $this->_helper->redirector('index');
    }
    
    public function indexAction()
    {
        $addForm    = new Admin_Form_AddTag();
        $deleteForm = new Admin_Form_DeleteTag();
        $renameForm = new Admin_Form_RenameTag();

        $this->view->message    = $this->_helper->FlashMessenger->getMessages();
        $this->view->addForm    = $addForm;
        $this->view->deleteForm = $deleteForm;
        $this->view->renameForm = $renameForm;
    }

    public function renameAction()
    {
        if($this->_request->isPost()) {
            $form   = new Admin_Form_RenameTag();
            $data   = $this->_request->getPost();

            if($form->isValid($data)) {
                $data   = $form->getValues();
                $tags   = new Application_Model_Tags();

                try {
                    $tags->rename($data['renameTags'], $data['newName']);
                    $this->_helper->FlashMessenger->addMessage('Tag was renamed successfully');
                } catch (Exception $e) {
                    $this->_helper->FlashMessenger->addMessage('An error occured: '.$e->getMessage());
                }
            } else {
                $this->_helper->FlashMessenger->addMessage('Unable to rename tag');
            }
        }

        $this->_helper->redirector('index');
    }
}
