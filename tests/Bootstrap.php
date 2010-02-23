<?php

define('APPLICATION_PATH', realpath(dirname(__FILE__).'/../application'));

set_include_path(
    '.'
    . PATH_SEPARATOR . realpath(APPLICATION_PATH.'/../library')
    . PATH_SEPARATOR . get_include_path()
);

define('APPLICATION_ENV', 'testing');

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);
$loader->suppressNotFoundWarnings(false);

include_once(APPLICATION_PATH.'/../scripts/load.sqlite.php');