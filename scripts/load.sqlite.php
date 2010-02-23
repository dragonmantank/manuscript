<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__).'/../application'));

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH.'/../library',
    get_include_path(),
)));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$getopt = new Zend_Console_Getopt(array(
   'withdata|w'     => 'Load the database with sample data',
    'env|e-s'       => 'Application environment to create the db',
    'help|h'        => 'Help -- usage message',
    'coverage-html' => 'Only here to satisfy the script when used with PHPUnit'
));

try {
    $getopt->parse();
} catch(Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    return false;
}

if($getopt->getOption('h')) {
    echo $getopt->getUsageMessage();
    return true;
}

$withData   = $getopt->getOption('w');
$env        = $getopt->getOption('e');

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (null === $env) ? 'development' : $env);

$application    = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH.'/configs/application.ini');

$bootstrap  = $application->getBootstrap();
$bootstrap->bootstrap('db');
$dbAdapter  = $bootstrap->getResource('db');
$testing    = ('testing' == APPLICATION_ENV  ? true : false);

if(!$testing) {
    echo 'Writing Database in (CTRL-C to cancel): '.PHP_EOL;
    for($x = 5; $x > 0; $x--) {
        echo $x.PHP_EOL;
        sleep(1);
    }
}

$options    = $bootstrap->getOption('resources');
$dbFile     = $options['db']['params']['dbname'];
if(file_exists($dbFile)) {
    unlink($dbFile);
}

try {
    $schemaSql = file_get_contents(dirname(__FILE__).'/schema.sqlite.sql');
    $dbAdapter->getConnection()->exec($schemaSql);
    chmod($dbFile, 0666);

    if(!$testing) {
        echo PHP_EOL.'Database Created'.PHP_EOL;
    }

    if($withData) {
        $dataSql = file_get_contents(dirname(__FILE__).'/data.sqlite.sql');
        $dbAdapter->getConnection()->exec($dataSql);
        if(!$testing) {
            echo 'Data Loaded'.PHP_EOL;
        }
    }
} catch(Exception $e) {
    echo "AN ERROR HAS OCCURED: ".PHP_EOL.$e->getMessage().PHP_EOL;
    return false;
}

return true;