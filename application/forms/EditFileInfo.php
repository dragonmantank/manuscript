<?php

class Application_Form_EditFileInfo extends Zend_Form
{
    public function init()
    {
        $title  = new Zend_Form_Element_Text('title');
        $tags   = new Zend_Form_Element_Text('tags');

        $title->addFilters(array('StripTags'))
              ->setRequired(true)
              ->addValidator('NotEmpty');

        $tags->addFilters(array('StripTags'))
             ->setRequired(true)
             ->addValidator('NotEmpty');

        $this->addElements(array(
            $title, $tags,
        ));

        foreach($this->getElements() as $element) {
			$element->removeDecorator('label');
			$element->removeDecorator('HtmlTag');
		}
    }
}