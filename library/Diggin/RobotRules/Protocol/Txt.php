<?php
class Diggin_RobotRules_Protocol_Txt implements Iterator
{
    private $_robotstxtstring = '';
    private $_robotstxt = '';
    private $_line = 0;
    private $_key = 0;

    
    public function __construct($robotstxt = '')
    {
        $this->_robotstxtstring = $robotstxt;
    }
    
    protected function _toArray($robotstxt)
    {
        
        $robotstxt = str_replace(chr(13).chr(10), chr(10), $robotstxt);
        $robotstxt = str_replace(array(chr(10), chr(13)), PHP_EOL, $robotstxt);

        $robotstxt = explode(PHP_EOL, $robotstxt);

        return $robotstxt;
    }

    protected function _getRobotsTxtArray()
    {
        if ($this->_robotstxt == '') {
            $this->_robotstxt = $this->_toArray($this->_robotstxtstring);
        }

        return $this->_robotstxt;
    }

    public function current()
    {
        //if $this->_robotstxt[$line] instanceof Diggin_RobotRules_Protocol_Txt_Line)
        //

        //@not_todoif none 'User-Agent: line' is handled as *
        // はgetRecordされたときにおこなう
        $record = new Diggin_RobotRules_Protocol_Txt_Record;
        do {
            //$record[] = Diggin_RobotRules_Protocol_Txt_Line::parse($this->_robotstxt[$this->_line]);
            //$this->_getRobotsTxtArray()->{$this->_line};
            $ra = $this->_getRobotsTxtArray();
            $record->append(Diggin_RobotRules_Protocol_Txt_Line::parse($ra[$this->_line]));
            $this->_line++; 
        } while (preg_match('!\w:!', $this->_robotstxt[$this->_line])); 
        
        return $record;
    }

    public function next()
    {
        do {
            $this->_line++;
        } while ($this->valid() && !preg_match('!\w:!', $this->_robotstxt[$this->_line]));
        $this->_key++;
    }

    public function valid()
    {
        return ($this->_line < count($this->_robotstxt)) ? true : false;
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
