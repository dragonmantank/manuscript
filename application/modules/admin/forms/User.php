<?php

class Admin_Form_User extends Zend_Form
{
    public function init()
    {
        $username       = new Zend_Form_Element_Text('username');
        $password       = new Zend_Form_Element_Password('password');
        $confPassword   = new Zend_Form_Element_Password('confPassword');
        $name           = new Zend_Form_Element_Text('name');
        $email          = new Zend_Form_Element_Text('email');
        $primaryGroup   = new Zend_Form_Element_Text('primaryGroup');
        $submit         = new Zend_Form_Element_Submit('submit');

        $username->setLabel('Username:')
                 ->setRequired(true)
                 ->addFilters(array('StringTrim', 'StripTags', 'Alnum'))
                 ->addValidator('NotEmpty');

        $password->setLabel('Password:')
                 ->setRequired(true)
                 ->addFilters(array('StringTrim', 'StripTags'))
                 ->addValidator('NotEmpty');
                 

        $confPassword->setLabel('Confirm Password:')
                     ->setRequired(true)
                     ->addFilters(array('StringTrim', 'StripTags'))
                     ->addValidator('NotEmpty')
                     ->addValidator('Identical');

        $name->setLabel('Name:')
             ->setRequired(true)
             ->addFilters(array('StringTrim', 'StripTags', 'Alnum'))
             ->addValidator('NotEmpty');

        $email->setLabel('Email Address:')
              ->setRequired(true)
              ->addFilters(array('StringTrim', 'StripTags'))
              ->addValidators(array('NotEmpty', 'EmailAddress'));

        $primaryGroup->setLabel('Primary Group:')
                     ->setRequired(true)
                     ->addFilters(array('StringTrim', 'StripTags', 'Alnum'))
                     ->addValidator('NotEmpty');

        $submit->setLabel('Add User');

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