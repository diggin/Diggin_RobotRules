<?php

namespace Diggin\RobotRules\Parser\HtmlMeta\Adapter;

require 'CommonTest.php';
use \Diggin\RobotRules\Parser\HtmlMeta\Adapter\CommonTest;

class DOMDocumentParserTest extends CommonTest
{
    protected $parser = 'DOMDocumentParser';

    public function parseHtml($html)
    {
        return DOMDocumentParser::parseHtml($html);
    }

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

    public function testHtmlNotHasMetaRobots()
    {
$html = <<<HTML
a
HTML;
        $ret = $this->parseHtml($html);
        $this->assertInstanceof('Diggin\RobotRules\Rules\HtmlMetaRobots', $ret);

    }


}
