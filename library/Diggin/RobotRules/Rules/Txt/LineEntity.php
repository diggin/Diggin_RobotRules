<?php
namespace Diggin\RobotRules\Rules\Txt;

class LineEntity
{
    //@see http://www.robotstxt.org/norobots-rfc.txt
    const EOL = "\r\n";

    private $_field;
    private $_value;
    private $_comment;

    // line separetor is CRLF
    public function __toString()
    {
        return $this->getField().': '.$this->getValue().
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
