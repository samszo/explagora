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

$www = "/Library/WebServer/Documents";
define ("ROOT_PATH",$www."/explagora");

set_include_path(get_include_path().PATH_SEPARATOR.$www."/Zend/library");

/** Zend_Application */
require_once 'Zend/Application.php';

//library supplémentaires
set_include_path(ROOT_PATH.'/library');
require_once( "bibtex-parser.php" );
require_once( "CAS/CAS.php");


// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();