<?php

namespace Diggin\RobotRules\Imported;

use Diggin\RobotRules\Parser\HtmlMeta\Adapter\DOMDocument;
use Diggin\RobotRules\Accepter\HtmlAccepter as Accepter;

// imported from cpan's HTML-RobotsMETA 0.00003 t/02_parse.t
class PerlHTMLRobotsMetaTest extends \PHPUnit_Framework_TestCase
{

    public function parseHtml($html)
    {
        return DOMDocument::parseHtml($html);
    }

    public function testNofollowNoindex()
    {
$html = <<<'EOM'
<html>
<head>
    <meta name="http-equiv" content="text/html; charset=utf-8">
    <meta name="robots"     content="nofollow, noindex">
</head>
<body>
    <div>hello, world</div>
</body>
</html>
EOM;

        $rule = $this->parseHtml($html);

        $accepter = new Accepter;
        $accepter->setRules($rule);
        $this->assertFalse($accepter->canFollow(), "Can't follow as expected");
        $this->assertFalse($accepter->canIndex(), "Can't index as expected");
        $this->assertTrue($accepter->canArchive(), "Can archive as expected");
   }

    public function testFollowNoindex()
    {
$html = <<<'EOM'
<html>
<head>
    <meta name="http-equiv" content="text/html; charset=utf-8">
    <meta name="robots"     content="follow, noindex">
</head>
<body>
    <div>hello, world</div>
</body>
</html>
EOM;
        $rule = $this->parseHtml($html);
        $accepter = new Accepter;
        $accepter->setRules($rule);
        $this->assertTrue($accepter->canFollow(), "Can follow as expected");
        $this->assertFalse($accepter->canIndex(), "Can't index as expected");
        $this->assertTrue($accepter->canArchive(), "Can archive as expected");
   }

    public function testNone()
    {
$html = <<<'EOM'
<html>
<head>
    <meta name="http-equiv" content="text/html; charset=utf-8">
    <meta name="robots"     content="NONE">
</head>
<body>
    <div>hello, world</div>
</body>
</html>
EOM;
        $rule = $this->parseHtml($html);
        $accepter = new Accepter;
        $accepter->setRules($rule);
        $this->assertFalse($accepter->canFollow(), "Can't follow as expected");
        $this->assertFalse($accepter->canIndex(), "Can't index as expected");
        $this->assertFalse($accepter->canArchive(), "Can't archive as expected");

   }

    public function testAll()
    {
$html = <<<'EOM'
<html>
<head>
    <meta name="http-equiv" content="text/html; charset=utf-8">
    <meta name="robots"     content="ALL">
</head>
<body>
    <div>hello, world</div>
</body>
</html>
EOM;
        $rule = $this->parseHtml($html);
        $accepter = new Accepter;
        $accepter->setRules($rule);
        $this->assertTrue($accepter->canFollow(), "Can follow as expected");
        $this->assertTrue($accepter->canIndex(), "Can index as expected");
        $this->assertTrue($accepter->canArchive(), "Can archive as expected");
   }

}
