<?php

//require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

error_reporting( E_ALL | E_STRICT );
set_include_path(dirname(dirname(__FILE__)).'/library/'. PATH_SEPARATOR. get_include_path());

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

Zend_Loader_Autoloader::getInstance()->registerNamespace('Diggin');
