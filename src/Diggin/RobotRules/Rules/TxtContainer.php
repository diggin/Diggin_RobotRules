<?php

namespace Diggin\RobotRules\Rules;

use AppendIterator;
use SplDoublyLinkedList;
use Diggin\RobotRules\Rules\Txt\LineEntity;
use Diggin\RobotRules\Rules\Txt\RecordEntity;

class TxtContainer extends AppendIterator implements Txt
{
    private $nonGroupMemberRecords;

    public static function factory(array $records = array(), array $nonGroupMemberRecords = array())
    {
        $nonWildCardRecords = new SplDoublyLinkedList();
        $wildCardRecords = new SplDoublyLinkedList();

        foreach($records as $record) {
            if (static::hasWildCardUserAgent($record)) {
                $wildCardRecords->push($record);
            } else {
                $nonWildCardRecords->push($record);
            }
        }

        $groupMemberRecords = new static();
        $groupMemberRecords->append($nonWildCardRecords);
        $groupMemberRecords->append($wildCardRecords);

        $groupMemberRecords->rewind();
        $groupMemberRecords->setNonGroupMemberRecords($nonGroupMemberRecords);

        return $groupMemberRecords;
    }

    public function setNonGroupMemberRecords($nonGroupMemberRecords)
    {
        $this->nonGroupMemberRecords = $nonGroupMemberRecords;
    }

    public function getNonGroupMemberRecord($key)
    {
        if (isset($this->nonGroupMemberRecords[$key])) {
            return $this->nonGroupMemberRecords[$key];
        }
    }

    protected static function hasWildCardUserAgent(RecordEntity $record)
    {
        $flag = false;
        if ($record->offsetExists('user-agent')) {
            /** @var LineEntity $line */
            foreach ($record->offsetGet('user-agent') as $line) {
                if ('*' === $line->getValue()) {
                    $flag = true;
                    break;
                }
            }
        }

        return $flag;
    }
}