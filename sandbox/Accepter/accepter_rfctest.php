<?php
set_include_path(dirname(__FILE__) . '/../../library' . PATH_SEPARATOR . get_include_path());

// Record Path URL path Matches
$txt = <<<'EOF'
/tmp /tmp yes
/tmp /tmp.html yes
/tmp /tmp/a.html yes
/tmp/ /tmp no
/tmp/ /tmp/ yes
/tmp/ /tmp/a.html yes
/a%3cd.html /a%3cd.html yes
/a%3Cd.html /a%3cd.html yes
/a%3cd.html /a%3Cd.html yes
/a%3Cd.html /a%3Cd.html yes
/a%2fb.html /a%2fb.html yes
/a%2fb.html /a/b.html no
/a/b.html /a%2fb.html no
/a/b.html /a/b.html yes 
/%7ejoe/index.html /~joe/index.html yes
/~joe/index.html /%7Ejoe/index.html yes
EOF;

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

class TestAccepter extends Diggin_RobotRules_Accepter_Txt
{
    function matchDisallow($v, $t)
    {
        $line = new Diggin_RobotRules_Protocol_Txt_Line;
        $line->setField('disallow');
        $line->setValue($v);

        $record = new Diggin_RobotRules_Protocol_Txt_Record;
        $record->append($line);

        return $this->_matchDisallow($record, $t);
    }
}

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

$accepter = new TestAccepter(); 
$accepter->setUserAgent('Googlebot');
$txts = explode("\n", $txt);
foreach ($txts as $t) {
    $path = explode(" ", $t);
    echo $t, PHP_EOL;

    var_dump($accepter->matchDisallow($path[0], $path[1]));
}

