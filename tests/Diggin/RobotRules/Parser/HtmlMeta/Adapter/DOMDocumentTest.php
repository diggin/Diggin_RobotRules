<?php

namespace Diggin\RobotRules\Parser\HtmlMeta\Adapter;

//require 'CommonTest.php';
use \Diggin\RobotRules\Parser\HtmlMeta\Adapter\CommonTest;

class DOMDocumentTest extends CommonTest
{
    protected $parser = 'DOMDocument';

    public function parseHtml($html)
    {
        return DOMDocument::parseHtml($html);
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


    public function testUpper()
    {
$html = <<<'EOM'
<html>
<head>
    <meta name="http-equiv" content="text/html; charset=utf-8">
    <meta name="roBots"     content="nofollow, noindex">
</head>
<body>
    <div>hello, world</div>
</body>
</html>
EOM;

        $rule = $this->parseHtml($html);
        $this->assertTrue($rule->hasContent('nofollow'));
    }


}
