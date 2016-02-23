<?php

namespace Diggin\RobotRules\Rules;

use Diggin\RobotRules\Rules\Txt\RecordEntity;
use Iterator;

class TxtContainer implements Iterator, Txt
{
    private $records;
    private $position = 0;
    
    /**
     * @param array
     */
    public function __construct($records)
    {
        $this->records = $records;
    }

    /**
     * @return RecordEntity
     */
    public function current()
    {
        return current($this->records);
    }

    public function next()
    {
        next($this->records);
        $this->position++;
    }

    public function valid()
    {
        return $this->position < count($this->records);
    }

    public function key()
    {
        return key($this->records);
    }

    public function rewind()
    {
        reset($this->records);
        $this->position = 0;
    }

}
