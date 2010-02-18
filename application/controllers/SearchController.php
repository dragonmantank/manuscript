<?php

class SearchController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $files  = new Application_Model_Files();

        $this->view->results    = $files->search($this->_request->getParam('search_query'));
    }
}