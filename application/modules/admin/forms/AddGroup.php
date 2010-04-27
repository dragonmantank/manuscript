<?php

class Admin_Form_AddGroup extends Zend_Form
{
    public function init()
    {
        $addGroup = new Zend_Form_Element_Text('addGroup');
        $submit = new Zend_Form_Element_Submit('submit');

        $addGroup->setLabel('Name:')
                 ->setRequired(true)
                 ->addFilter('StripTags');

        $submit->setLabel('Add New Group');

        $this->addElements(array(
            $addGroup, $submit,
        ));
    }
}
