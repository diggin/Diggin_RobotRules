<?php

namespace Diggin\RobotRules\Rules;

use Diggin\RobotRules\Rules\Txt\LineEntity;
use Diggin\RobotRules\Rules\Txt\RecordEntity;
use Iterator;

class TxtContainer implements Iterator, Txt
{
    private $records;

    /**
     * @param array
     */
    public function __construct($records)
    {
        $doublyLinkedList = new \SplDoublyLinkedList();
        foreach($records as $record) {
            if ($this->hasWildCard($record)) {
                $doublyLinkedList->push($record);
            } else {
                $doublyLinkedList->unshift($record);
            }
        }

        $doublyLinkedList->rewind();

        $this->records = $doublyLinkedList;
    }

    public function add(RecordEntity $record)
    {
        if ($this->hasWildCard($record)) {
            $this->records->push($record);
        } else {
            $this->records->unshift($record);
        }
    }

    /**
     * @return RecordEntity
     */
    public function current()
    {
        return $this->records->current();
    }

    public function next()
    {
        $this->records->next();
    }

    public function valid()
    {
        return $this->records->valid();
    }

    public function key()
    {
        return $this->records->key();
    }

    public function rewind()
    {
        $this->records->rewind();
    }

    protected function hasWildCard(RecordEntity $record)
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