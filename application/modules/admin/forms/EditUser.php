<?php

class Admin_Form_EditUser extends Zend_Form
{
    public function init()
    {
        $groupModel     = new Application_Model_Groups();
        $groups         = $groupModel->fetchAll();
        $groupOptions   = array();
        foreach($groups as $group) {
            $groupOptions[$group->id] = $group->name;
        }

        $username       = new Zend_Form_Element_Text('username');
        $password       = new Zend_Form_Element_Password('password');
        $confPassword   = new Zend_Form_Element_Password('confPassword');
        $name           = new Zend_Form_Element_Text('name');
        $email          = new Zend_Form_Element_Text('email');
        $primaryGroup   = new Zend_Form_Element_Select('primaryGroup');
        $submit         = new Zend_Form_Element_Submit('submit');

        $username->setLabel('Username:')
                 ->setRequired(true)
                 ->setAttrib('readonly', true)
                 ->addFilters(array('StringTrim', 'StripTags', 'Alnum'))
                 ->addValidator('NotEmpty');

        $password->setLabel('Password:')
                 ->addFilters(array('StringTrim', 'StripTags'));
                 

        $confPassword->setLabel('Confirm Password:')
                     ->addFilters(array('StringTrim', 'StripTags'))
                     ->addValidator('Identical');

        $name->setLabel('Name:')
             ->setRequired(true)
             ->addFilters(array('StringTrim', 'StripTags'))
             ->addValidator('NotEmpty');

        $email->setLabel('Email Address:')
              ->setRequired(true)
              ->addFilters(array('StringTrim', 'StripTags'))
              ->addValidators(array('NotEmpty', 'EmailAddress'));

        $primaryGroup->setLabel('Primary Group:')
                     ->setRequired(true)
                     ->addFilters(array('StringTrim', 'StripTags', 'Alnum'))
                     ->setMultiOptions($groupOptions)
                     ->addValidator('NotEmpty');

        $submit->setLabel('Edit User');

        $this->addElements(array(
            $username, $password, $confPassword, $name, $email, $primaryGroup,
            $submit,
        ));
    }

    public function isValid($data)
    {
        $confPassword   = $this->getElement('confPassword');
        $confPassword->getValidator('Identical')->setToken($data['password'])->setMessage('Passwords do not match');

        return parent::isValid($data);
    }
}
