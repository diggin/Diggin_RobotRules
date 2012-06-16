Diggin_RobotRules
=========================

PHP parser/handler for Robots Exclusion Protocol (robots.txt and more..) 

Master: [![Build Status](https://secure.travis-ci.org/diggin/Diggin_RobotRules.png?branch=master)](http://travis-ci.org/diggin/Diggin_RobotRules)

Features
--------

- implements http://www.robotstxt.org/norobots-rfc.txt
    - [DONE] "3.2.2 The Allow and Disallow lines" - as test-case
    - [DONE] "4.Examples" as test-case

- passing Nutch's test code
    [ref](https://github.com/apache/nutch/blob/trunk/src/plugin/lib-http/src/test/org/apache/nutch/protocol/http/api/TestRobotRulesParser.java)
    - [DONE] @see tests/Diggin/RobotRules/Imported/NutchTest.php
- parsing & handling html-meta

ToDos
-----
- handle Crawl-Delay
- sync or testing a little pattern w/ Google Test robots.txt tool
    - https://www.google.com/webmasters/tools/robots-analysis-ac?hl=en&siteUrl=http://yourdomain
- rewrite with PHPPEG.(because current preg* base parser makes difficulty.)
- more test, refactoring on and on..

USAGE
-----
``` php
<?php
use Diggin\RobotRules\Accepter\TxtAccepter;
use Diggin\RobotRules\Parser\TxtStringParser;

$robotstxt = <<<'ROBOTS'
# sample robots.txt
User-agent: YourCrawlerName
Disallow:

User-agent: *
Disallow: /aaa/ #comment
ROBOTS;

$accepter = new TxtAccepter;
$accepter->setRules(TxtStringParser::parse($robotstxt));

$accepter->setUserAgent('foo');
var_dump($accepter->isAllow('/aaa/')); //false
var_dump($accepter->isAllow('/b.html')); //true

$accepter->setUserAgent('YourCrawlerName');
var_dump($accepter->isAllow('/aaa/')); // true
```

INSTALL
-------
Diggin_RobotRules is following PSR-0,
so to register namespace Diggin\RobotRules into your ClassLoader.

several way to install.
- pear package (coming soon..)
- $php composer.phar install
- write dependency for your composer.json

```
{
    "require": {
        "diggin/Diggin_RobotRules": "dev-master"
    }
}
```

License
-------
Diggin_RobotRules is licensed under new-bsd.


Reference & alternatives in others language.
---------------------------

- Perl
    - http://search.cpan.org/~dmaki/Gungho-0.09008/docs/ja/Gungho/Component/RobotRules.pod
    - http://homepage3.nifty.com/hippo2000/perltips/WWW/RobotRules.html
- Python
    - http://docs.python.org/library/robotparser.html
    - http://svn.python.org/projects/python/trunk/Lib/robotparser.py
- Ruby
    - https://github.com/knu/webrobots
