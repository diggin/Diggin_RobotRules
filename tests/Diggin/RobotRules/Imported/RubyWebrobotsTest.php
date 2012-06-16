<?php

namespace Diggin\RobotRules\Impoted;

use Diggin\RobotRules\Accepter\TxtAccepter;
use Diggin\RobotRules\Parser\TxtStringParser;
use Zend_Uri as Uri;
    //Zend_Uri_Http as Http;

/**
 * This file is borrowed ruby's webrobots test.
 *
 */
class RubyWebrobotsTest extends \PHPUnit_Framework_TestCase
{
    private $accepterSet = array();

    public function setUp()
    {
        //$this->markTestSkipped();
        $accepterRandombot = new TxtAccepter;
        $accepterRandombot->setUserAgent('RandomBot');
        $accepterGoodBot = new TxtAccepter;
        $accepterGoodBot->setUserAgent('GoodBot');
        $accepterEvilBot = new TxtAccepter;
        $accepterEvilBot->setUserAgent('EvilBot');

        $this->accepterSet['RandomBot'] = $accepterRandombot;
        $this->accepterSet['GoodBot'] = $accepterGoodBot;
        $this->accepterSet['EvilBot'] = $accepterEvilBot;
    }

    /**
     * @dataProvider provideContextRobotstxtWithSomeRules
     */
    public function testContextRobotstxtWithSomeRules($ua, $url, $allowed)
    {
        $this->markTestSkipped('currently undexpected test-case exists (Google tools tells other expected result)');

        $accepter = $this->accepterSet[$ua];
        $accepter->setRules(TxtStringParser::parse($rule = $this->findRule($url)));

        if ($allowed) {
            $this->assertTrue($accepter->isAllow($url), 'rule is '.PHP_EOL. $rule);
        } else {
            $this->assertFalse($accepter->isAllow($url), 'rule is '.PHP_EOL. $rule);
        }
    }

    public function provideContextRobotstxtWithSomeRules()
    {
        return array(
          array('GoodBot','http://www.example.org/2heavy/index.php', false),
          array('GoodBot','http://www.example.org/2HEAVY/index.php', true),
          array('GoodBot', Uri::factory('http://www.example.org/2heavy/index.php'), false),
          array('GoodBot','http://www.example.org/2heavy/index.html', true),
          array('GoodBot','http://www.Example.Org/2heavy/index.html', true),
          array('GoodBot','http://www.example.org/2heavy/index.htm', false),
          array('GoodBot','http://www.Example.Org/2heavy/index.htm', false),
        );
    }

    /**
     * @todo implements in library
     */
    public function findRule($url)
    {
        $url = \Zend_Uri::factory($url);
        $url->setHost(strtolower($url->getHost()));
        $url->setPath('/robots.txt');
        return $this->getRule((string)$url);
    }

    protected function getRule($robotstxturl)
    {
        switch ($robotstxturl) {
            case 'http://www.example.org/robots.txt' :
                return <<<'TXT'
# Punish evil bots
User-Agent: evil
Disallow: /
Disallow-Not: /-# parser teaser

User-Agent: good
# Be generous to good bots
Disallow: /2heavy/
Allow: /2heavy/*.htm
Disallow: /2heavy/*.htm$

User-Agent: *
Disallow: /2heavy/
Disallow: /index.html
# Allow takes precedence over Disallow if the pattern lengths are the same.
Allow: /index.html
TXT;

            case 'http://www.example.com/robots.txt':
                return <<<'TXT'
# Default rule is evaluated last even if it is put first.
User-Agent: *
Disallow: /2heavy/
Disallow: /index.html
# Allow takes precedence over Disallow if the pattern lengths are the same.
Allow: /index.html

# Punish evil bots
User-Agent: evil
Disallow: /

User-Agent: good
# Be generous to good bots
Disallow: /2heavy/
Allow: /2heavy/*.htm
Disallow: /2heavy/*.htm$
TXT;
    case 'http://koster1.example.net/robots.txt' :
          <<<'TXT'
User-Agent: *
Disallow: /tmp
TXT;
    case 'http://koster2.example.net/robots.txt' :
          <<<'TXT'
User-Agent: *
Disallow: /tmp/
TXT;
    case 'http://koster3.example.net/robots.txt' :
          <<<'TXT'
User-Agent: *
Disallow: /a%3cd.html
TXT;
    case 'http://koster4.example.net/robots.txt' :
          <<<'TXT'
User-Agent: *
Disallow: /a%3Cd.html
TXT;
    case 'http://koster5.example.net/robots.txt':
          <<<'TXT'
User-Agent: *
Disallow: /a%2fb.html
TXT;
    case 'http://koster6.example.net/robots.txt':
          <<<'TXT'
User-Agent: *
Disallow: /a/b.html
TXT;
    case 'http://koster7.example.net/robots.txt':
          <<<'TXT'
User-Agent: *
Disallow: /%7ejoe/index.html
TXT;
    case 'http://koster8.example.net/robots.txt':
          <<<'TXT'
User-Agent: *
Disallow: /~joe/index.html
TXT;
    default :
          //raise 
          throw new \Exception("$url is not supposed to be fetched");
            break;
            }
    }

}

