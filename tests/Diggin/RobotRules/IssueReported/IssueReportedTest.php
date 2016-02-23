<?php
namespace Diggin\RobotRules\Rules;

use Diggin\RobotRules\Accepter\TxtAccepter;
use Diggin\RobotRules\Parser\TxtStringParser;

class IssueReportedTest extends \PHPUnit_Framework_TestCase
{
    public function testWildCard_is_expected_to_be_accepted_lastly()
    {
        $txt = <<<EOF
User-agent: *
Disallow: /foo/bar/

User-agent: Googlebot
Disallow: /test/
EOF;

        $accepter = new TxtAccepter();
        $accepter->setRules(TxtStringParser::parse($txt));

        $accepter->setUserAgent('Googlebot');
        $this->assertFalse($accepter->isAllow('/test/1.jpg'));
        $this->assertTrue($accepter->isAllow('/foo/bar/baz.html'));
    }
}