<?php

namespace DigginTests\RobotRules\Accepter;

use ReflectionMethod;
use Diggin\RobotRules\Accepter\TxtAccepter;
use Diggin\RobotRules\Parser\TxtStringParser;
use Diggin\RobotRules\Rules\Txt\RecordEntity;
use Diggin\RobotRules\Rules\Txt\LineEntity;

/**
 */
class TxtTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Diggin\RobotRules\Exception
     */
    public function testFailingInvalidArg()
    {
        $accepter = new TxtAccepter(); 
        $accepter->isAllow(false); 
    }

    /**
     * @expectedException \Diggin\RobotRules\Exception
     */
    public function testFailingIfRulesNotSet()
    {
        $accepter = new TxtAccepter(); 
        $accepter->isAllow('http://example.com/'); 
    }
    
    public function testParseCommentAndBlankline()
    {
$txt = <<<EOF
#  robots.txt for www.example.com

User-agent: *
Disallow: /aaa/
EOF;

        $accepter = new TxtAccepter(); 
        $accepter->setRules(TxtStringParser::parse($txt));

        $accepter->setUserAgent('webcrawler');
        $this->assertFalse($accepter->isAllow('/aaa/'));

$txt = <<<EOF
User-agent: webcrawler
Disallow:

#  robots.txt for www.example.com

User-agent: *
Disallow: /aaa/
EOF;
        $accepter->setRules(TxtStringParser::parse($txt));

        $accepter->setUserAgent('webcrawler');
        $this->assertTrue($accepter->isAllow('/bb/'));

$txt = <<<EOF
User-agent: webcrawler
#comment
User-agent: Googlebot
#comment
Disallow: /test
EOF;
        $accepter->setRules(TxtStringParser::parse($txt));

        $accepter->setUserAgent('webcrawler');
        $this->assertFalse($accepter->isAllow('/test/'));

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

        $accepter = new TxtAccepter(); 
        $accepter->setRules(TxtStringParser::parse($txt));

        $accepter->setUserAgent('Googlebot');
        $this->assertFalse($accepter->isAllow('/test/1.jpg'));
        $this->assertFalse($accepter->isAllow('/foo/baz/baz.html'));
        $this->assertTrue($accepter->isAllow('/foo/bar/baz.html'));

    }

    public function testUserAgentCaseInsensitive()
    {

$txt = <<<EOF
User-agent: Googlebot
Disallow: /test/

User-agent: *
Disallow: /foo/bar/
EOF;

        $accepter = new TxtAccepter(); 
        $accepter->setRules(TxtStringParser::parse($txt));

        $accepter->setUserAgent('googleBot');
        $this->assertFalse($accepter->isAllow('/test/1.jpg'));
        $this->assertTrue($accepter->isAllow('/foo/bar/baz.html'));

    }

    /**
     * @group robotsrfc
     * 
     * This test for norobots-rfc.txt
     * http://www.robotstxt.org/norobots-rfc.txt
     *   4. Examples
     */
    public function testRobotstxtorgExamples()
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

        $accepter = new TxtAccepter(); 

        $accepter->setRules(TxtStringParser::parse($txt));
        $checks = explode("\n", $check);
        foreach ($checks as $c) {
            $s = preg_split('/ +/', $c);

            //echo $s[0], PHP_EOL;
            $accepter->setUserAgent('unhipbot');

            if ((trim($s[1]) === 'Yes') ? true: false) {
                $this->assertTrue($accepter->isAllow($s[0]));
            } else {
                $this->assertFalse($accepter->isAllow($s[0]));
            }

            $accepter->setUserAgent('webcrawler');

            if ((trim($s[2]) === 'Yes') ? true: false) {
                $this->assertTrue($accepter->isAllow($s[0]));
            } else {
                $this->assertFalse($accepter->isAllow($s[0]));
            }

            $accepter->setUserAgent('otherbot');

            if ((trim($s[3]) === 'Yes') ? true: false) {
                $this->assertTrue($accepter->isAllow($s[0]), $c);
            } else {
                $this->assertFalse($accepter->isAllow($s[0]), $c);
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

    /**
     * @group robotsrfc
     *
     * This test for norobots-rfc.txt
     * http://www.robotstxt.org/norobots-rfc.txt
     *  3.2.2 The Allow and Disallow lines
     */
    public function testAllowDisallowLines()
    {
        //Record Path        URL path         Matches
$exmples = <<<EOF
/tmp               /tmp               yes
/tmp               /tmp.html          yes
/tmp               /tmp/a.html        yes
/tmp/              /tmp               no
/tmp               /tmp               yes
/tmp/              /tmp/              yes
/tmp/              /tmp/a.html        yes
/a%3cd.html        /a%3cd.html        yes
/a%3Cd.html        /a%3cd.html        yes
/a%3cd.html        /a%3Cd.html        yes
/a%3Cd.html        /a%3Cd.html        yes
/a%2fb.html        /a%2fb.html        yes
/a%2fb.html        /a/b.html          no
/a/b.html          /a%2fb.html        no
/a/b.html          /a/b.html          yes
/%7ejoe/index.html /~joe/index.html   yes
/~joe/index.html   /%7Ejoe/index.html yes
EOF;

        $lines = explode("\n", $exmples);

        $accepter = new TxtAccepter(); 

        foreach ($lines as $line) {
            $s = preg_split('/ +/', $line);
            $disallow = $s[0];
$txt = <<<ROBOTSTXT
      User-agent: dummycrawler
      Disallow: $disallow
ROBOTSTXT;
            $accepter->setRules(TxtStringParser::parse($txt));
            $accepter->setUserAgent('dummycrawler');


            if ((boolean)(trim($s[2]) !== 'yes')) {
                $this->assertTrue($accepter->isAllow($s[1]), 'example line is '. $line. PHP_EOL .'robots.txt'.PHP_EOL.$txt);
            } else {
                $this->assertFalse($accepter->isAllow($s[1]), ' example line is '. $line. PHP_EOL .'robots.txt'.PHP_EOL.$txt);
            }
        }
    }

    /**
     * @dataProvider decodeProvider
     */
    public function testDecode($record_path, $url_path, $expected)
    {
        $accepter = new ReflectionMethod('Diggin\RobotRules\Accepter\TxtAccepter', 'matchCheck');
        $accepter->setAccessible(true);

        $record = new RecordEntity;
        $line = new LineEntity;
        $line->setField('disallow');
        $line->setValue($record_path);
        $record->append($line);

        $ret = $accepter->invoke(new TxtAccepter, 'disallow', $record, $url_path); 
        $this->assertSame($expected, (boolean)$ret, $record_path. ' '. $url_path);
    }

    public function decodeProvider()
    {
        return array(
            array('/a%2fb.html', '/a%2fb.html', true),             
            array('/a%2fb.html', '/a/b.html', false),             
            array('/a/b.html', '/a%2fb.html', false),             
            array('/a/b.html', '/a/b.html', true),             
        );
    }

    public function testWildcardShouldMatch()
    {
        $accepter = new ReflectionMethod('Diggin\RobotRules\Accepter\TxtAccepter', 'matchCheck');
        $accepter->setAccessible(true);

        $record = new RecordEntity;
        $line = new LineEntity;
        $line->setField('disallow');
        $line->setValue($record_path = '/compare/*/apply*');
        $record->append($line);

        $match = $accepter->invoke(new TxtAccepter, 'disallow', $record, $path = '/compare/*/apply*'); 
        $this->assertTrue((boolean)$match, $record_path. ' '. $path);
    }

}
