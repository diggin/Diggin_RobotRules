<?php
set_include_path(dirname(__FILE__) . '/../../library' . PATH_SEPARATOR . get_include_path());

// Record Path URL path Matches
      # /robots.txt for http://www.fict.org/
      # comments to webmaster@fict.org
$txt = <<<'EOF'
      User-agent: unhipbot
      Disallow: /

      User-agent: webcrawler
      User-agent: excite
      Disallow: 

      User-agent: *
      Disallow: /org/plans.html
      Allow: /org/
      Allow: /serv
      Allow: /test
      Allow: /~mak
      Disallow: /
EOF;
//   The following matrix shows which robots are allowed to access URLs:

//                                               unhipbot webcrawler other
//                                                        & excite
$check = <<<'EOF'
http://www.fict.org/                         No       Yes       No
http://www.fict.org/index.html               No       Yes       No
http://www.fict.org/robots.txt               Yes      Yes       Yes
http://www.fict.org/server.html              No       Yes       Yes
http://www.fict.org/services/fast.html       No       Yes       Yes
http://www.fict.org/services/slow.html       No       Yes       Yes
http://www.fict.org/orgo.gif                 No       Yes       No
http://www.fict.org/org/about.html           No       Yes       Yes
http://www.fict.org/org/plans.html           No       Yes       No
http://www.fict.org/%7Ejim/jim.html          No       Yes       No
http://www.fict.org/%7Emak/mak.html          No       Yes       Yes
EOF;

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

$accepter = new Diggin_RobotRules_Accepter_Txt(); 
//$accepter->setUserAgent('Googlebot');
//$accepter->setUserAgent('unhipbot');
$accepter->setProtocol($protocol = new Diggin_RobotRules_Protocol_Txt($txt));
$checks = explode("\n", $check);
foreach ($checks as $c) {
    $s = preg_split('/ +/', $c);

    echo $s[0], PHP_EOL;
    $accepter->setUserAgent('unhipbot');
goto case3;

    echo $s[1], ' '; var_dump($accepter->isAllow($s[0]));
    $accepter->setUserAgent('webcrawler');
    echo $s[2], ' '; var_dump($accepter->isAllow($s[0]));
case3:
    $accepter->setUserAgent('otherbot');
    echo $s[3], ' '; var_dump($accepter->isAllow($s[0]));

}
