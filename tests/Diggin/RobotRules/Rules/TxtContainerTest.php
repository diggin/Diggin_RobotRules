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
        $lineAgent->setValue('test');
        $lineDisallow = new Line;
        $lineDisallow->setField('disallow');
        $lineDisallow->setValue('/test');

        $record = new Record;
        $record->append($lineAgent);
        $record->append($lineDisallow);

        $txt = new TxtContainer(array($record));

        $this->assertInstanceof('\\Diggin\\RobotRules\\Rules\\Txt\\RecordEntity', $txt->current());

        $record2 = new Record;
        $record2->append($lineAgent->setValue('test2'));
        $record2->append($lineDisallow->setValue('/test2'));

        $txt = new TxtContainer(array($record, $record2));

        foreach ($txt as $record) {
            $this->assertInstanceof('\\Diggin\\RobotRules\\Rules\\Txt\\RecordEntity', $record);
        }
    }
}

