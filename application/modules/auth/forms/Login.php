<?php

class Auth_Form_Login extends Zend_Form
{
    public function init()
    {
        $username   = new Zend_Form_Element_Text('username');
        $password   = new Zend_Form_Element_Password('password');
        $submit     = new Zend_Form_Element_Submit('submit');

        $username->setLabel('Username:')
                 ->addFilter(new Zend_Filter_Alnum())
                 ->setRequired(true);

        $password->setLabel('Password:')
                 ->setRequired(true);

        $submit->setLabel('Log In');

        $this->addElements(array(
            $username, $password, $submit,
        ));
    }
}