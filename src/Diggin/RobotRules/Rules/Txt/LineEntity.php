<?php
namespace Diggin\RobotRules\Rules\Txt;

class LineEntity
{
    //@see http://www.robotstxt.org/norobots-rfc.txt
    const EOL = "\r\n";

    private $field;
    private $value;
    private $comment;

    public function setField($field)
    {
        $this->field = $field;
    }

    public function getField()
    {
        if (isset($this->field)) {
            return $this->field;
        } else {
            return '';
        }
    }
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

    // line separetor is CRLF
    public function toString()
    {
        return $this->getField().': '.$this->getValue().
            (isset($this->comment) ? ' # '.$this->getComment(): '').self::EOL;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
