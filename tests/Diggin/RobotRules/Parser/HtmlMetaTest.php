<?php
namespace Diggin\RobotRules\Parser;

/**
 */
class HtmlMetaTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAdapter()
    {
        $parser = new HtmlMeta;
        $this->assertInstanceof('\Diggin\RobotRules\Parser\HtmlMeta\Adapter', $parser->getAdapter());
    }

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

}
