<?php
// only tests & demo

$vendor = __DIR__.'/vendor';
$library = __DIR__.'/library';
set_include_path($vendor.PATH_SEPARATOR.
                 $library.PATH_SEPARATOR.
                 get_include_path());

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->registerNamespace('Diggin');

