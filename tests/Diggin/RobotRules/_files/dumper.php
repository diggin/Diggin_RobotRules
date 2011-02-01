<?php

//include dirname(dirname(dirname(__DIR__))).DIRECTORY_SEPARATOR.'bootstrap.php';

set_include_path(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/library/'. PATH_SEPARATOR. get_include_path());

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
Zend_Loader_Autoloader::getInstance()->registerNamespace('Diggin');


//if ($argv[1])

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.')) as $f) {
    if ($f->isFile() && ('robots.txt'== $f->getFileName())) {
        echo $f, PHP_EOL;

        $result = Diggin\RobotRules\Parser\TxtStringParser::parse(file_get_contents($f));
        var_dump($result);
        echo PHP_EOL;
    }
}
