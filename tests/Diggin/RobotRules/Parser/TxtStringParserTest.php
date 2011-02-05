<?php
namespace Diggin\RobotRules\Parser;
use Diggin\RobotRules\Rules\Txt\LineEntity as Line;

class SpecifiedFieldValueIterator extends \IteratorIterator
{
    private $pos = false;
    private $fieldKey = 'sitemap';
    private $fieldnum = 0;

    public function setFieldKey($key)
    {
        $this->fieldKey = $key;
    }
    
    public function current()
    {
        $fieldArray = $this->getInnerIterator()->current()->offsetGet($this->fieldKey);
        
        return $fieldArray[(int)$this->pos]->getValue();
    }

    public function next()
    {
        if ($this->pos === false) {
            $fieldArray = $this->getInnerIterator()->current()->offsetGet($this->fieldKey);
            $this->fieldnum = count($fieldArray);
            $this->pos = 1;
        } else if ($this->pos < $this->fieldnum){
            $this->pos++;
        }
        
        if ($this->fieldnum === ($this->pos)) {
            $this->getInnerIterator()->next();
            $this->pos = false;
        }
    }

    public function valid()
    {
        return ($this->getInnerIterator()->current() instanceof \Diggin\RobotRules\Rules\Txt\RecordEntity);
    }
}

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

Sitemap: http://example.com/sitemap.xml
EOF;

        $txtContainer = $txtContainer0 = TxtStringParser::parse($txt);

        $re = new SpecifiedFieldValueIterator($txtContainer0);

        foreach ($re as $v) {
            var_dump($v, PHP_EOL);
        }
    }

    public function testParseLine()
    {
        // not robots.txt line
        $ret = TxtStringParser::parseLine('');
        $this->assertFalse($ret);
        $ret = TxtStringParser::parseLine('foo bar');
        $this->assertFalse($ret);
        $ret = TxtStringParser::parseLine('foo#bar');
        $this->assertFalse($ret);

        $ret = TxtStringParser::parseLine('Disallow: /foo/');
        $this->assertTrue($ret instanceof Line);
        $this->assertEquals('disallow', $ret->getField());
        $this->assertEquals('/foo/', $ret->getValue());
        $this->assertEquals("", $ret->getComment());
        
        // handle comment
        $ret = TxtStringParser::parseLine("Disallow: /foo/ #comment1");
        $this->assertTrue($ret instanceof Line, var_export($ret,true));
        $this->assertEquals("comment1", $ret->getComment());


        $ret = TxtStringParser::parseLine("User-Agent: Agent1 Agent2 #comment2");
        $this->assertInternalType('array', $ret);
        $agent1 = $ret[0];
        $agent2 = $ret[1];
        $this->assertEquals('Agent1', $agent1->getValue());
        $this->assertEquals('comment2', $agent1->getComment());
        $this->assertEquals('Agent2', $agent2->getValue());
        $this->assertEquals('comment2', $agent2->getComment());
    }
}

