<?php

class FilesController extends Zend_Controller_Action
{
    public function addAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if(count($_FILES) && !empty($_FILES['file']['tmp_name'])) {
            $files  = new Application_Model_Files();
            $tags   = explode(',', $this->_request->getParam('tags'));
        
            $fileData   = array(
                'filename'  => $_FILES['file']['name'],
                'mimetype'          => $_FILES['file']['type'],
                'size'          => $_FILES['file']['size'],
                'tmp_name'          => $_FILES['file']['tmp_name'],
                'originalAuthor'            => Zend_Auth::getInstance()->getIdentity()->id,
                'title'             => $this->_request->getParam('title'),
            );

            $id = $files->add($fileData, $tags);

            $this->_helper->redirector->gotoUrl('files/info/file/'.$id);
        } else {
            $this->_helper->FlashMessenger->addMessage('File uploaded was too large. Please change your settings or upload a smaller file');
            $this->_helper->redirector('index', 'index', 'index');
        }
    }

    public function addrevisionAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $files  = new Application_Model_Files();

        $fileData   = array(
            'fileId'            => $this->_request->getParam('file'),
            'filename'          => $_FILES['newRevision']['name'],
            'mimetype'          => $_FILES['newRevision']['type'],
            'size'              => $_FILES['newRevision']['size'],
            'tmp_name'          => $_FILES['newRevision']['tmp_name'],
            'originalAuthor'    => Zend_Auth::getInstance()->getIdentity()->id,
        );

        $id = $files->addRevision($fileData);

        $this->_helper->redirector->gotoUrl('files/info/file/'.$id);
    }

    public function addcommentAction()
    {
        $newComment = strip_tags(trim($this->_request->getParam('newComment')));
        $ref        = $this->_request->getParam('file');

        $files  = new Application_Model_Files();
        $file   = $files->find($ref);

        $files->addComment(array(
            'comment'   => $newComment,
            'fileId'    => $file->id,
            'version'   => $file->revision,
            'author'    => Zend_Auth::getInstance()->getIdentity()->id,
        ));

        $this->_helper->redirector->gotoUrl('/files/info/file/'.$ref);
    }

    public function createAction()
    {
        $form = new Application_Form_CreateFile();

        if($this->_request->isPost()) {
            if($this->_request->getParam('texttocopy') == null) {
                $data   = $this->_request->getPost();

                if($form->isValid($data)) {
                    $this->_helper->layout->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);

                    $data = $form->getValues();

                    $files  = new Application_Model_Files();
                    $tags   = explode(',', $data['tags']);

                    $fileData   = array(
                        'filename'          => str_replace(' ', '_', strtolower($data['title'])).'.html',
                        'mimetype'          => 'text/html',
                        'size'              => 0,
                        'originalAuthor'    => Zend_Auth::getInstance()->getIdentity()->id,
                        'title'             => $data['title'],
                        'body'              => $data['file'],
                    );

                    $id = $files->create($fileData, $tags);

                    $this->_helper->redirector->gotoUrl('files/info/file/'.$id);
                } else {
                    $this->view->message = 'There was a problem with the entry';
                }
            } else {
                $this->view->texttocopy = $this->_request->getParam('texttocopy');
                $this->view->title      = $this->_request->getParam('title');
            }
        }

        $this->view->form   = $form;
    }

    public function deleteAction()
    {
        $files  = new Application_Model_Files();
        $id     = $this->_request->getParam('file');
        $file   = $files->find($id);

        if($this->_request->isPost()) {
            $data   = $this->_request->getPost();

            if(array_key_exists('deleteYes', $data)) {
                $files->delete($id);
                $this->_helper->redirector->gotoUrl('/');
            } else {
                $this->_helper->redirector->gotoUrl('files/info/file/'.$id);
            }
        }
        $this->view->file   = $file;
    }

    public function downloadAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $detail = $this->_request->getParam('detail');
        $files  = new Application_Model_Files();
        if($detail != null) {
            $file   = $files->findRevision($this->_request->getParam('file'), $detail);
        } else {
            $file   = $files->find($this->_request->getParam('file'));
        }

        $path   = realpath(APPLICATION_PATH.'/../data/'.$file->directory).'/'.$file->fsFilename;

        header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: ' . $file->mimetype);
		header('Content-Disposition: attachment; filename="' . $file->filename . '";');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . $file->size);
		readfile($path);
        die();
    }

    public function editinfoAction()
    {
        $files  = new Application_Model_Files();
        $file   = $files->find($this->_request->getParam('file'));
        $form   = new Application_Form_EditFileInfo();

        if($this->_request->isPost()) {
            $data   = $this->_request->getPost();

            if($form->isValid($data)) {
                $data   = $form->getValues();

                try {
                    $files->updateInfo($data, $file->id);
                    $this->_helper->redirector->gotoUrl('files/info/file/'.$file->id);
                } catch (Exception $e) {
                    $this->view->message    = 'An error occured: '.$e->getMessage();
                }
            } else {
                $this->view->message    = 'There was a problem with the form submission';
            }
        } else {
            $form->populate($file->toArray());
            $tags   = array();
            foreach($this->view->getFileTags($file->id) as $tag) {
                $tags[] = $tag['name'];
            }
            $form->getElement('tags')->setValue(implode(', ', $tags));
        }

        $this->view->file   = $file;
        $this->view->form   = $form;
    }

    public function indexAction()
    {
        
    }

    public function infoAction()
    {
        $files  = new Application_Model_Files();
        $file   = $files->find($this->_request->getParam('file'));

        $this->view->file   = $file;
        $this->view->revisions  = $files->fetchRevisions($file->id);
    }

    public function viewAction()
    {
        $this->_forward('download');
    }
}
