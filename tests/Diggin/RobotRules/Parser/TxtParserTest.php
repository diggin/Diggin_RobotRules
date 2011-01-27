<?php
namespace Diggin\RobotRules\Parser;
use Diggin\RobotRules\Rules\Txt\LineEntity as Line;

class TxtParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseLine()
    {
        // not robots.txt line
        $ret = TxtParser::parseLine('');
        $this->assertFalse($ret);
        $ret = TxtParser::parseLine('foo bar');
        $this->assertFalse($ret);
        $ret = TxtParser::parseLine('foo#bar');
        $this->assertFalse($ret);

        $ret = TxtParser::parseLine('Disallow: /foo/');
        $this->assertTrue($ret instanceof Line);
        $ret = TxtParser::parseLine("Disallow: /foo/ #comment1");
        $this->assertTrue($ret instanceof Line, var_export($ret,true));
    }
}

