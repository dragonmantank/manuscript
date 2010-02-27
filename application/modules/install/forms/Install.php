<?php

class Install_Form_Install extends Zend_Form
{
    public function init()
    {
        $dbType     = new Zend_Form_Element_Select('dbType');
        $host       = new Zend_Form_Element_Text('host');
        $username   = new Zend_Form_Element_Text('username');
        $password   = new Zend_Form_Element_Text('password');
        $dbname     = new Zend_Form_Element_Text('dbname');
        $install    = new Zend_Form_Element_Submit('install');

        $dbType->setRequired(true)
               ->setMultiOptions(array(
                    null            => 'Select One...',
                    'sqlite'        => 'SQLite 3',
                    'sqlserver2k8'  => 'SQL Server 2008',
               ));
        
        $host->setLabel('Database Host:')
             ->addFilter('StripTags');
        
        $username->setLabel('Database Username:')
                 ->addFilter('StripTags');

        $password->setLabel('Database Password:')
                 ->addFilter('StripTags');

        $dbname->setLabel('Database Name:')
               ->addFilter('StripTags');

        $install->setLabel('Install Manuscript');

        $this->addElements(array(
            $dbType, $host, $username, $password, $dbname, $install,
        ));
    }
}
