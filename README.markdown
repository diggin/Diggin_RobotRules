Diggin_RobotRules - README
=========================

Master: [![Build Status](https://secure.travis-ci.org/diggin/Diggin_RobotRules.png?branch=master)](http://travis-ci.org/diggin/Diggin_RobotRules)


TODOs & current status

- implements http://www.robotstxt.org/norobots-rfc.txt
    - [DONE] "3.2.2 The Allow and Disallow lines" - as test-case
    - [DONE] "4.Examples" as test-case

- passing Nutch's test code
    - [DONE] @see tests/Diggin/RobotRules/Imported/NutchTest.php

- handle Crawl-Delay
- sync or testing a little pattern w/ Google Test robots.txt tool
    - @see http://www.google.com/support/webmasters/bin/answer.py?answer=156449
- parsing & handling html-meta

- rewrite with PHPPEG.(because current preg* base parser makes difficulty.)
- more test
- refactoring on and on..
