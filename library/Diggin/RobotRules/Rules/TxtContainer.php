<?php

namespace Diggin\RobotRules\Rules;

class TxtContainer implements Txt
{
    private $records;
    
    /**
     * @param Traversble|array
     */
    public function __construct($records)
    {
        $this->records = $records;
    }

    public function current()
    {
        return $this->records[$this->point];
    }

    public function next()
    {
        
    }

    public function valid()
    {
        
    }

    public function key()
    {
        return $this->_key;
    }

    public function rewind()
    {
        $this->_line = 0;
    }

}
