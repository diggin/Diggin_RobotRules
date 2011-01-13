<?php
namespace Diggin\RobotRules\Rules\Txt;
use Diggin\RobotRules\Rules\Txt\LineEntity as Line;

class RecordEntity implements \ArrayAccess
{
    private $_fields = array();
    
    public function offsetExists($offset)
    {
        return isset($this->_fields[strtolower($offset)]);
    }

    public function offsetSet($offset, $lines)
    {
        $this->_fields[$offset] = $lines;
    }

    public function offsetGet($offset)
    {
        //@todo not strtowloer,CamelUppder
        
        if (array_key_exists(strtolower($offset), $this->_fields)) {
            return $this->_fields[strtolower($offset)];
        } else {
            return null;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->_fields[$offset]);
    }

    /** Diggin_RobotRules_Protocol_Txt_line  **/
    public function append(Line $line)
    {
        if (array_key_exists($field = $line->getField(), $this->_fields)) {
            $this->_fields[$field][count($this->_fields[$field])] = $line;
        } else {
            $this->_fields[$field][0] = $line;
        }
    }

    /**
     * note: User-Agent must first. 
     * but Allow & Disallow which not define sort
     * http://www.robotstxt.org/norobots-rfc.txt 3.2
     */
    private function _fieldsort($x, $y)
    {
        if (strtolower($x) === 'user-agent') return -10;
        if (strtolower($y) === 'user-agent') return 10;

        if (strtolower($x) === 'disallow' && strtolower($y) == 'allow') {
            return -1;
        } else if (strtolower($x) === 'allow' && strtolower($y) == 'disallow') {
            return 1;
        }
        return 0;
    }

    public function __toString()
    {
        $fieldstring = "";
        uksort($this->_fields, array($this, '_fieldsort'));
        foreach ($this->_fields as $field) {
            $fieldstring .= implode("\n", $field);
        }
        return $fieldstring;
    }
}
