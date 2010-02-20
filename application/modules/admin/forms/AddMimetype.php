<?php

class Admin_Form_AddMimetype extends Zend_Form
{
    public function init()
    {
        $mimetypes  = new Application_Model_Mimetypes();
        $mimetypes  = $mimetypes->fetchNew();

        $newMimetype    = new Zend_Form_Element_Select('newMimetype');
        $description    = new Zend_Form_Element_Text('description');
        $submit         = new Zend_Form_Element_Submit('submit');

        if($mimetypes != null) {
            $newMimetype->setMultiOptions($mimetypes)
                        ->setLabel('New Mimetypes:');
        } else {
            $newMimetype->setLabel('There are no new mimetypes to set');
        }

        $description->setLabel('Description:')
                    ->setRequired(true)
                    ->addFilter('StripTags');

        $submit->setLabel('Save Mimetype');

        $this->addElements(array(
            $newMimetype, $description, $submit,
        ));
    }
}
