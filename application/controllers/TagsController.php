<?php

class TagsController extends Zend_Controller_Action
{
    public function viewAction()
    {
        $tagId  = $this->_request->getParam('tag');
        $tags   = new Application_Model_Tags();
        $tag    = $tags->find($tagId);
        $files  = $tags->fetchFiles($tagId);

        $this->view->files      = $files;
        $this->view->tagName    = $tag->name;
    }
}