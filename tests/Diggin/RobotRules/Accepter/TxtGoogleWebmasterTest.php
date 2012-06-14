<?php

namespace DigginTests\RobotRules\Accepter;

/**
 * a test
 * https://www.google.com/webmasters/tools/robots-analysis-ac?hl=ja&siteUrl=http://yoursite/
 */
class TxtGoogleWebasterTest extends \PHPUnit_Framework_TestCase
{
    
    protected function getAccpeter($txt, $useragent = 'Googlebot')
    {
        $accepter = new \Diggin\RobotRules\Accepter\TxtAccepter(); 
        $accepter->setRules(\Diggin\RobotRules\Parser\TxtStringParser::parse($txt));
        $accepter->setUserAgent($useragent);
        return $accepter;
    }
    
    public function testCommentAndUseragent()
    {
$txt = <<<EOF
User-agent: Googlebot-Mobile
Disallow: /path/
# aa
User-agent: Googlebot
Disallow: /foo/bar/
EOF;
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot')->isAllow('/path/to/'));
        $this->assertFalse($this->getAccpeter($txt, 'Googlebot-Mobile')->isAllow('/path/to/'));
        $this->assertFalse($this->getAccpeter($txt, 'Googlebot')->isAllow('/foo/bar/'));
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot-Mobile')->isAllow('/foo/bar/'));

    }

    public function testWildCard()
    {
$txt = <<<EOF
User-agent: Googlebot-Mobile
Disallow: /path/*/
Allow: /path/*/*

User-agent: Googlebot
Disallow: /foo/*?
Allow: /foo/*.jpg?
EOF;

        $this->assertTrue($this->getAccpeter($txt, 'Googlebot')->isAllow('/path/to/'));
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot-Mobile')->isAllow('/path/to/'), 'Allowed by "Allowed by line 3"');

        $this->assertTrue($this->getAccpeter($txt, 'Googlebot')->isAllow('/path/to/x'));
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot-Mobile')->isAllow('/path/to/'), 'Allowed by "Allowed by line 3"');

        // Googlebot don't treat "?" as wildcard
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot')->isAllow('/foo/bar/'));
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot-Mobile')->isAllow('/foo/bar/'));

        $this->assertTrue($this->getAccpeter($txt, 'Googlebot')->isAllow('/foo/bar.jpg'));
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot-Mobile')->isAllow('/foo/bar.jpg'));

        $this->assertTrue($this->getAccpeter($txt, 'Googlebot')->isAllow('/foo/bar.jpgX'));
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot-Mobile')->isAllow('/foo/bar.jpgX'));
    
    }

    public function testDoller()
    {
$txt = <<<'EOF'
User-agent: Googlebot
Disallow: /*
Allow: /index.htm$
EOF;

        $this->assertFalse($this->getAccpeter($txt, 'Googlebot')->isAllow('/export01/'));
        $this->assertFalse($this->getAccpeter($txt, 'Googlebot')->isAllow('/index.php'), $txt);
        $this->assertTrue($this->getAccpeter($txt, 'Googlebot')->isAllow('/index.htm'), $txt);
        $this->assertFalse($this->getAccpeter($txt, 'Googlebot')->isAllow('/index.html'));
    
    }
}
