<?php
//http://www.robotstxt.org/orig.html
//
//http://docs.python.org/library/robotparser.html
//http://svn.python.org/projects/python/trunk/Lib/robotparser.py
//
//http://search.cpan.org/~dmaki/Gungho-0.09008/docs/ja/Gungho/Component/RobotRules.pod
//http://homepage3.nifty.com/hippo2000/perltips/WWW/RobotRules.html
//
//http://googlewebmastercentral.blogspot.com/2008/06/improving-on-robots-exclusion-protocol.html
//http://www.google.com/support/webmasters/bin/answer.py?answer=35237&query=robots.txt&topic=&type=
//http://web-tan.forum.impressrd.jp/e/2008/02/27/2710

class Diggin_RobotRules 
    //implements Zend_Filter_Interface ?
{
//    static function import()
//    {
//        return null;
//    }

    protected $config = array(
       'accepter' => 'a'
    );
    
    public function __construct(Zend_Config $config)
    {
    }


//    public function setAccepter();
}
