<?php

class Admin_Form_AddGroup extends Zend_Form
{
    public function init()
    {
        $groups     = new Application_Model_Groups();
        $perms      = $groups->fetchPermissionsList();
        $permissions    = array();
        foreach($perms as $permission) {
            $permissions[$permission['id']] = $permission['name'];
        }

        $addGroup = new Zend_Form_Element_Text('addGroup');
        $permission = new Zend_Form_Element_MultiCheckbox('permission');
        $submit = new Zend_Form_Element_Submit('submit');

        $addGroup->setLabel('Name:')
                 ->setRequired(true)
                 ->addFilter('StripTags');

        $permission->setLabel('Permissions:')
                   ->setMultiOptions($permissions);

        $submit->setLabel('Add New Group');

        $this->addElements(array(
            $addGroup, $permission, $submit,
        ));
    }
}
