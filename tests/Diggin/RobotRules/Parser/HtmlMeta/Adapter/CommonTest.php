<?php

namespace Diggin\RobotRules\Parser\HtmlMeta\Adapter;

abstract class CommonTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    public function parseHtml($html)
    {
        $parser = $this->parser;
        return $parser::parseHtml($html);
    }

    /*
    public function testBlank()
    {
$html = <<<HTML

HTML;
        try {
            $ret = $this->parseHtml($html);
            $this->fail('blank not allowed');
        } catch (\Exception $e) {
        }
    }
    */

    public function testHtmlNotHasMetaRobots()
    {
$html = <<<HTML
a
HTML;
        $ret = $this->parseHtml($html);
        //$this->assertInstanceof('', );

    }


}
