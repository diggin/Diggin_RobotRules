<?php

namespace Diggin\RobotRules\Rules;

use Iterator;

class TxtContainer implements Iterator, Txt
{
    private $records;
    private $position = 0;
    
    /**
     * @param Traversble|array
     */
    public function __construct($records)
    {
        $this->records = $records;
    }

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
