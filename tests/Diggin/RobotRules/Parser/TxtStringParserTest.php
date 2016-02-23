<?php
namespace DigginTests\RobotRules\Parser;

use Diggin\RobotRules\Parser\TxtStringParser;
use Diggin\RobotRules\Rules\Txt\LineEntity as Line;
use Diggin\RobotRules\Rules\Txt\LineEntity;
use Diggin\RobotRules\Rules\Txt\RecordEntity;
use Diggin\RobotRules\Rules\Txt\SpecifiedFieldValueIterator;

class TxtStringParserTest extends \PHPUnit_Framework_TestCase
{
    public function testBlank()
    {
$txt = <<<EOF

EOF;

        $txtContainer = TxtStringParser::parse($txt);
        $this->assertEquals(null, $txtContainer->current(), 'parser should return null, if no field');
    }

    public function testUnexpectedBlank()
    {
$txt = <<<EOF
User-agent: *
Disallow: /path/

Allow: /path/to/
EOF;

        $txtContainer = TxtStringParser::parse($txt);
        $record = $txtContainer->current();

        $this->assertEquals('/path/', current($record->offsetGet('disallow'))->getValue());
        $this->assertEquals('/path/to/', current($record->offsetGet('allow'))->getValue());

        // Accepter test
        $accepter = new \Diggin\RobotRules\Accepter\TxtAccepter;
        $accepter->setRules($txtContainer);

        $this->assertFalse($accepter->isAllow('/path/to1/aaa'));
        $this->assertTrue($accepter->isAllow('/path/to/aaa'));
    }

    public function testGetSitemap()
    {


$txt = <<<EOF
Sitemap: http://example.com/sitemap1.xml
Sitemap: http://example.com/sitemap2.xml

User-agent: *
Disallow: /path/
Sitemap: http://example.com/sitemap3.xml
Sitemap: http://example.com/sitemap4.xml

User-agent: Robot
Disallow: /path/

Sitemap: http://example.com/sitemap.xml
EOF;

        $txtContainer = TxtStringParser::parse($txt);

        /** @var RecordEntity $record */
        $record= $txtContainer->current();

        /** @var LineEntity $v */
        foreach ($record->offsetGet('sitemap') as $v) {
            $this->assertRegExp('#http#', $v->toString());
        }


$txt = <<<EOF
User-agent: *

User-agent: Test
Sitemap: http://example.com/sitemap.xml
EOF;

        $txtContainer = TxtStringParser::parse($txt);

        $lines = $txtContainer->current()->offsetGet('sitemap');

        $this->assertRegExp('#http#',$lines[0]->getValue());

$txt = <<<EOF
# comment
Sitemap: http://example.com/sitemap.xml
EOF;

        $txtContainer = TxtStringParser::parse($txt);

        $lines = $txtContainer->current()->offsetGet('sitemap');

        $this->assertRegExp('#http#', $lines[0]->getValue());


    }

    public function testParseLine()
    {
        // not robots.txt Line
        $parser = new TxtStringParser;
        $ret = $parser->parseLine('');
        $this->assertFalse($ret);
        $ret = $parser->parseLine('foo bar');
        $this->assertFalse($ret);
        $ret = $parser->parseLine('foo#bar');
        $this->assertFalse($ret);

        $ret = $parser->parseLine('Disallow: /foo/');
        $this->assertTrue($ret instanceof Line);
        $this->assertEquals('disallow', $ret->getField());
        $this->assertEquals('/foo/', $ret->getValue());
        $this->assertEquals("", $ret->getComment());
        
        // handle comment
        $ret = $parser->parseLine("Disallow: /foo/ #comment1");
        $this->assertTrue($ret instanceof Line, var_export($ret,true));
        $this->assertEquals("comment1", $ret->getComment());

        // nutch 
        $parser->setConfig(array(
               'space_as_separator' => true
            )
        );

        $ret = $parser->parseLine("User-Agent: Agent1 Agent2 #comment2");
        $this->assertInternalType('array', $ret);
        $agent1 = $ret[0];
        $agent2 = $ret[1];
        $this->assertEquals('Agent1', $agent1->getValue());
        $this->assertEquals('comment2', $agent1->getComment());
        $this->assertEquals('Agent2', $agent2->getValue());
        $this->assertEquals('comment2', $agent2->getComment());
    }
}

