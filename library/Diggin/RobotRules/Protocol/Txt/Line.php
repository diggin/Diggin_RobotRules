<?php
class Diggin_RobotRules_Protocol_Txt_Line
{
    //@see http://www.robotstxt.org/norobots-rfc.txt
    const EOL = "\r\n";

    private $_field;
    private $_value;
    private $_comment;

    /**
     *
     * @string $line
     * @return Diggin_RobotRules_Line
     */
    public static function parse($line)
    {
        //preg
        //preg_match('!\s*(\w*):\s*(\w*)\s*#*(\s*(\w)*\s*)!i', 'User-Agent: Google #d#d', $match);
        
        preg_match('!\s*([^:]*):\s*([^#]*)\s*#*\s*([^\z]*)!i', 
                    $line, $match);
                    
        $line = new self;
        $line->setField(strtolower($match[1]));
        $line->setValue($match[2]);
        $line->setComment($match[3]);
        
        return $line;
    }

    // line separetor is CRLF
    //
    public function __toString()
    {
        return $this->getField().':'.$this->getValue().
            (isset($this->_comment) ? ' # '.$this->getComment(): '').self::EOL;
    }

    public function setField($field)
    {
        $this->_field = $field;
    }
    public function getField()
    {
        if (isset($this->_field)) {
            return $this->_field;
        } else {
            return '';
        }
    }
    public function setValue($value)
    {
        $this->_value = $value;
    }
    public function getValue()
    {
        return $this->_value;
    }
    public function setComment($comment)
    {
        $this->_comment = $comment;
    }
    public function getComment()
    {
        return $this->_comment;
    }
}
