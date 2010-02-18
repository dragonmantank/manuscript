<?php

class Admin_Form_RenameTag extends Zend_Form
{
    public function init()
    {
        $tagModel       = new Application_Model_Tags();
        $tags           = $tagModel->fetchAll(null, 'name ASC');
        $tagsOptions    = array();
        foreach($tags as $tag) {
            $tagsOptions[$tag->id]  = $tag->name;
        }

        $renameTags = new Zend_Form_Element_Select('renameTags');
        $newName    = new Zend_Form_Element_Text('newName');
        $submit     = new Zend_Form_Element_Submit('submit');

        $renameTags->setLabel('Original tag:')
                   ->addMultiOptions($tagsOptions);

        $newName->setLabel('New Name:')
                ->setRequired(true);
        
        $submit->setLabel('Delete Tag');

        $this->addElements(array(
            $renameTags, $newName, $submit,
        ));
    }
}