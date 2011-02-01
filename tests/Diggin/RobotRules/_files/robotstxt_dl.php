<?php

// simple robots.txt downloader

$url = $argv[1];
$robotstxt = file_get_contents($url.'/robots.txt');

mkdir($dir = __DIR__.DIRECTORY_SEPARATOR.rawurlencode($url));

file_put_contents($dir.DIRECTORY_SEPARATOR.'robots.txt', $robotstxt);
