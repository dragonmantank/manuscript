<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$localConfigFile = APPLICATION_PATH.'/configs/local.ini';
if(is_file($localConfigFile)) {
    $config = new Zend_Config($application->getOptions(), true);
    $local  = new Zend_Config_Ini($localConfigFile);
    $config->merge($local);
    $application->setOptions($config->toArray());
}

$application->bootstrap()
            ->run();