<?php
set_include_path(dirname(__FILE__) . '/../../library' . PATH_SEPARATOR . get_include_path());

$txt = <<<EOF
User-agent: Google
User-agent: Infoseek
Disallow:

User-Agent: Googlebot
Disallow: /cgi-bin/
Disallow: /*.gif$

User-agent: *
Disallow: /

EOF;




require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

$accepter = new Diggin_RobotRules_Accepter_Txt(); 
$accepter->setUserAgent('Googlebot');
$accepter->setProtocol(new Diggin_RobotRules_Protocol_Txt($txt));
var_dump($accepter->isAllow('http://test.org/cgi-bin/test.cgi')); //boolean
var_dump($accepter->isAllow('http://test.org/test.html')); //boolean
