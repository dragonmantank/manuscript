<?php

class FilesController extends Zend_Controller_Action
{
    public function infoAction()
    {
        $files  = new Application_Model_Files();
        $file   = $files->find($this->_request->getParam('file'));

        $this->view->file   = $file;
    }

    public function addAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $files  = new Application_Model_Files();
        $tags   = explode(',', $this->_request->getParam('tags'));

        $fileData   = array(
            'originalFilename'  => $_FILES['file']['name'],
            'mimetype'          => $_FILES['file']['type'],
            'filesize'          => $_FILES['file']['size'],
            'tmp_name'          => $_FILES['file']['tmp_name'],
            'author'            => Zend_Auth::getInstance()->getIdentity()->id,
            'title'             => $this->_request->getParam('title'),
        );

        $id = $files->add($fileData, $tags);

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
            'version'   => $file->version,
            'author'    => Zend_Auth::getInstance()->getIdentity()->id,
        ));

        $this->_helper->redirector->gotoUrl('/files/info/file/'.$ref);
    }

    public function downloadAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $files  = new Application_Model_Files();
        $file   = $files->find($this->_request->getParam('file'));

        $path   = realpath(APPLICATION_PATH.'/../data/'.$file->directory).'/'.$file->reference;

        header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: ' . $file->mimetype);
		header('Content-Disposition: attachment; filename="' . $file->originalFilename . '";');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . $file->filesize);
		readfile($path);
        die();
    }

    public function viewAction()
    {
        $this->_forward('download');
    }
}
