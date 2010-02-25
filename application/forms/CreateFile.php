<?php

class Application_Form_CreateFile extends Zend_Form
{
    public function init()
    {
        $title  = new Zend_Form_Element_Text('title');
        $tags   = new Zend_Form_Element_Text('tags');
        $file   = new Zend_Form_Element_Textarea('file');
        $submit = new Zend_Form_Element_Submit('submit');

        $title->setLabel('Title:')
              ->addFilter('StripTags')
              ->setRequired(true);

        $tags->setLabel('Tags:')
             ->addFilter('StripTags')
             ->setRequired(true);

        $file->setRequired(true);

        $submit->setLabel('Create File');

        $this->addElements(array(
            $title, $tags, $file, $submit,
        ));
    }
}
