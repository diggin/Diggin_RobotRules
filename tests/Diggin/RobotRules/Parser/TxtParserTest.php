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
        $this->assertEquals('disallow', $ret->getField());
        $this->assertEquals('/foo/', $ret->getValue());
        $this->assertEquals("", $ret->getComment());
        
        // handle comment
        $ret = TxtParser::parseLine("Disallow: /foo/ #comment1");
        $this->assertTrue($ret instanceof Line, var_export($ret,true));
        $this->assertEquals("comment1", $ret->getComment());


        $ret = TxtParser::parseLine("User-Agent: Agent1 Agent2 #comment2");
        $this->assertInternalType('array', $ret);
        $agent1 = $ret[0];
        $agent2 = $ret[1];
        $this->assertEquals('Agent1', $agent1->getValue());
        $this->assertEquals('comment2', $agent1->getComment());
        $this->assertEquals('Agent2', $agent2->getValue());
        $this->assertEquals('comment2', $agent2->getComment());
    }
}

