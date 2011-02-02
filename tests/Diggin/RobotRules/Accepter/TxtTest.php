<?php

/**
 */
class Diggin_RobotRules_Accepter_TxtTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @expectedException \Exception
     */
    public function testFailingInvalidArg()
    {
        $accepter = new Diggin\RobotRules\Accepter\TxtAccepter(); 
        $accepter->isAllow(false); 
    }

    /**
     * @expectedException \Exception
     */
    public function testFailingIfRulesNotSet()
    {
        $accepter = new Diggin\RobotRules\Accepter\TxtAccepter(); 
        $accepter->isAllow('http://example.com/'); 
    }
    
    public function testParseCommentAndBlankline()
    {
$txt = <<<EOF
#  robots.txt for www.example.com

User-agent: *
Disallow: /aaa/
EOF;

        $accepter = new Diggin\RobotRules\Accepter\TxtAccepter(); 
        $accepter->setRules(Diggin\RobotRules\Parser\TxtStringParser::parse($txt));

        $accepter->setUserAgent('webcrawler');
        $this->assertFalse($accepter->isAllow('/aaa/'));

$txt = <<<EOF
User-agent: webcrawler
Disallow:

#  robots.txt for www.example.com

User-agent: *
Disallow: /aaa/
EOF;
        $accepter->setRules(Diggin\RobotRules\Parser\TxtStringParser::parse($txt));

        $accepter->setUserAgent('webcrawler');
        $this->assertTrue($accepter->isAllow('/bb/'));
    }

    public function testMultiUseragentSet()
    {
        //$this->markTestSkipped();

$txt = <<<EOF
User-agent: Googlebot
Disallow: /test/

User-agent: Googlebot2
Disallow: /test/

User-agent: Googlebot
Disallow: /foo/baz

User-agent: *
Disallow: /foo/bar/
EOF;

        $accepter = new Diggin\RobotRules\Accepter\TxtAccepter(); 
        $accepter->setRules(Diggin\RobotRules\Parser\TxtStringParser::parse($txt));

        $accepter->setUserAgent('Googlebot');
        $this->assertFalse($accepter->isAllow('/test/1.jpg'));
        $this->assertFalse($accepter->isAllow('/foo/baz/baz.html'));
        $this->assertTrue($accepter->isAllow('/foo/bar/baz.html'));

    }
        

    public function testRobotstxtorg()
    {

// Record Path URL path Matches
      # /robots.txt for http://www.fict.org/
      # comments to webmaster@fict.org
$txt = <<<EOF
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
$check = <<<EOF
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

        $accepter = new Diggin\RobotRules\Accepter\TxtAccepter(); 

        //$accepter->setRules(new Diggin\RobotRules\Parser\TxtParser($txt));
        $accepter->setRules(Diggin\RobotRules\Parser\TxtStringParser::parse($txt));
        $checks = explode("\n", $check);
        foreach ($checks as $c) {
            $s = preg_split('/ +/', $c);

            //echo $s[0], PHP_EOL;
            $accepter->setUserAgent('unhipbot');

            if (($s[1] == 'Yes') ? true: false) {
                $this->assertTrue($accepter->isAllow($s[0]));
            } else {
                $this->assertFalse($accepter->isAllow($s[0]));
            }

            $accepter->setUserAgent('webcrawler');

            if (($s[2] == 'Yes') ? true: false) {
                $this->assertTrue($accepter->isAllow($s[0]));
            } else {
                //var_dump($s[0]);
                $this->assertFalse($accepter->isAllow($s[0]));
            }


            $accepter->setUserAgent('otherbot');

            if (($s[3] == 'Yes') ? true: false) {
                $this->assertTrue($accepter->isAllow($s[0]));
            } else {
                $this->assertFalse($accepter->isAllow($s[0]));
            }

            //$accepter->setUserAgent('otherbot');

            //echo $s[1], ' '; var_dump($accepter->isAllow($s[0]));
            //$accepter->setUserAgent('webcrawler');
            //echo $s[2], ' '; var_dump($accepter->isAllow($s[0]));
            //$accepter->setUserAgent('otherbot');
            //echo $s[3], ' '; var_dump($accepter->isAllow($s[0]));

        }
        //die;
    }

}
