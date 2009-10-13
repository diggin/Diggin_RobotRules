<?php
set_include_path(dirname(dirname(__FILE__)) . '/library' . PATH_SEPARATOR . get_include_path());
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

$robots = <<<EOF
User-agent: Google
User-agent: Infoseek
Disallow:

User-Agent: Googlebot
Disallow: /cgi-bin/
Disallow: /*.gif$

User-agent: *
Disallow: /     

EOF;



$robots = new Diggin_RobotRules_Protocol_Txt($robots);


foreach ($robots as $key => $record)
{
    echo 111;
    var_dump($record);
    //var_dump($record);
}


