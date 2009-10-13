<?php
set_include_path(dirname(__FILE__) . '/../../library' . PATH_SEPARATOR . get_include_path());

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

$accepter = new Diggin_RobotRules_Accepter($userAgent); //Zend_Http_Client or Name
//$accepter->setTargetUrl();
$accepter->addRule(Diggin_RobotRules_Protocol::factory('txt', $txt));
$accepter->addRule();
$accepter->addRule();

$accepter->isAllowed(); //boolean
