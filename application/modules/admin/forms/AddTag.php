<?php

class Admin_Form_AddTag extends Zend_Form
{
    public function init()
    {
        $addTag = new Zend_Form_Element_Text('addTag');
        $submit = new Zend_Form_Element_Submit('submit');

        $addTag->setLabel('Name:')
               ->setRequired(true)
               ->addFilter('StripTags');

        $submit->setLabel('Add New Tag');

        $this->addElements(array(
            $addTag, $submit,
        ));
    }
}