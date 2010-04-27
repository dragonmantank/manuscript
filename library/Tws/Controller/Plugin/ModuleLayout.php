<?php
/**
 * ModuleLayout Front Controller Plugin for Zend Framework
 * 
 * @license http://www.tankersleywebsolutions.com/licenses/bsd.txt New BSD License 
 * @author Chris Tankersley <chris@tankersleywebsolutions.com>
 * @copyright Chris Tankersley 2009
 * @version 0.1
 */

/**
 * Dynamically sets a layout based upon the Module name
 *
 * Checks to see if a layout file exists for a module base upon the module name,
 * and if the file is found, resets the layout file. The layout will be left at
 * the last set layout if no file is found for a module.
 *
 * @class Tws_Controller_Plugin
 */
class Tws_Controller_Plugin_ModuleLayout extends Zend_Controller_Plugin_Abstract
{
    /**
     * Determines the module name for a request and dynamically changes the layout
     *
     * After a request has been dispatched, the module will look in the Layout
     * path for a layout in the form of '<modulename>-layout'. If the file is
     * found, the layout will be switched. Otherwise, the layout is left alone.
     *
     * @param Zend_Controller_Request_Abstract $request Request
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $filename   = $request->getModuleName() . '-layout';
        $layout     = Zend_Layout::getMvcInstance();
        $layoutPath = $layout->getLayoutPath();

        if( is_file($layoutPath.'/'.$filename.'.phtml')) {
            $layout->setLayout($filename);
        }
    }
}
