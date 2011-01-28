<?php
namespace Diggin\RobotRules\Rules;
use Diggin\RobotRules\Rules\Txt\RecordEntity as Record;
use Diggin\RobotRules\Rules\Txt\LineEntity as Line;

class TxtContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testStandardSet()
    {
        $lineAgent = new Line;
        $lineAgent->setField('User-agent');
        $lineAgent->setValue('test0');
        $lineDisallow = new Line;
        $lineDisallow->setField('disallow');
        $lineDisallow->setValue('/test0');

        $record = new Record;
        $record->append($lineAgent);
        $record->append($lineDisallow);

        $txt = new TxtContainer(array($record));

        $this->assertInstanceof('\\Diggin\\RobotRules\\Rules\\Txt\\RecordEntity', $txt->current());

        $record2 = new Record;
        $lineAgent1 = clone $lineAgent;
        $lineAgent1->setValue('test1');
        $record2->append($lineAgent1);
        $lineDisallow1 = clone $lineDisallow;
        $lineDisallow1->setValue('/test1');
        $record2->append($lineDisallow1);

        $txt = new TxtContainer(array($record, $record2));

        foreach ($txt as $key => $record) {
            $this->assertInstanceof('\\Diggin\\RobotRules\\Rules\\Txt\\RecordEntity', $record);
            $disallows = $record['disallow'];
            $disallow = current($disallows);
            $this->assertEquals('/test'.$key, $disallow->getValue());
        }
    }
}

