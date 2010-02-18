<?php

class Admin_Form_DeleteTag extends Zend_Form
{
    public function init()
    {
        $tagModel       = new Application_Model_Tags();
        $tags           = $tagModel->fetchAll(null, 'name ASC');
        $tagsOptions    = array();
        foreach($tags as $tag) {
            $tagsOptions[$tag->id]  = $tag->name;
        }

        $deleteTags = new Zend_Form_Element_Select('deleteTags');
        $submit     = new Zend_Form_Element_Submit('submit');

        $deleteTags->setLabel('')
                   ->addMultiOptions($tagsOptions);

        $submit->setLabel('Delete Tag');

        $this->addElements(array(
            $deleteTags, $submit,
        ));
    }
}