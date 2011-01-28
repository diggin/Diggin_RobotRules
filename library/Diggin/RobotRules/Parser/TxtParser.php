<?php

namespace Diggin\RobotRules\Parser;
use Diggin\RobotRules\Rules\Txt as TxtRules;
use Diggin\RobotRules\Rules\Txt\LineEntity as Line;
use Diggin\RobotRules\Rules\Txt\RecordEntity as Record;

class TxtParser implements TxtRules
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
        // normalize line
        $robotstxt = str_replace(chr(13).chr(10), chr(10), $robotstxt);

        // CRLF
        //http://web.archive.org/web/20080702125527/http://www.robotstxt.org/norobots-rfc.txt
        $robotstxt = str_replace(array(chr(10), chr(13)), chr(13).chr(10), $robotstxt);

        $robotstxt = explode(chr(13).chr(10), $robotstxt);

        return $robotstxt;
    }

    public static function parseLine($line)
    {        
        preg_match('!\s*([^:]*):\s*([^#]*)\s*#*\s*([^\z]*)!i', 
                    $line, $match);

        // ignore unmatched txt line.
        if (count($match) < 2) {
            return false;
        }

        $values = preg_split('#\s+#', trim($match[2]));

        if (count($values) > 1) {
            $lines = array();
            $line = new Line;
            $line->setField(strtolower(trim($match[1])));
            $line->setComment(trim($match[3]));
            foreach ($values as $k => $v) {
                $line->setValue($v);
                $lines[$k] = clone $line; 
            }
            return $lines;
        } else {     
            $line = new Line;
            $line->setField(strtolower(trim($match[1])));
            $line->setValue(trim($match[2]));
            $line->setComment(trim($match[3]));
            return $line;
        }
    }

    protected function _getRobotsTxtArray()
    {
        //init check
        if (!preg_match('!\w\s*:!m', $this->_robotstxtstring)) {
            //require_once 'Exception';
            //throw new Exception("Invalid format");
        }

        if ($this->_robotstxt == '') {
            $robotstxt = $this->_toArray($this->_robotstxtstring);
            $this->_robotstxt = $robotstxt;
        }

        return $this->_robotstxt;
    }

    public function current()
    {
        $record = new Record;
        do {
            $ra = $this->_getRobotsTxtArray();
            if ($line = static::parseLine($ra[$this->_line])) {
                if (is_array($line)) foreach ($line as $v) { 
                    $record->append($v);
                } else {
                    $record->append($line);
                }
            }
            $this->_line++;
        } while (isset($this->_robotstxt[$this->_line]) and
                 preg_match('!\w\s*:!', $this->_robotstxt[$this->_line])); 
        
        return $record;
    }

    public function next()
    {
        do {
            $this->_line++;
        } while ($this->valid() && !preg_match('!\w\s*:!', $this->_robotstxt[$this->_line]));
        $this->_key++;
    }

    public function valid()
    {
        if (!preg_match('!\w\s*:!s', $this->_robotstxtstring)) return false;
        return ($this->_line < count($this->_robotstxt)) ? true : false;
    }

    public function key()
    {
        return $this->_key;
    }

    public function rewind()
    {
        // initialize to array
        $this->_getRobotsTxtArray();

        $this->_line = 0;
        do {
            if (preg_match('!\w\s*:!', $this->_robotstxt[$this->_line])) {
                break;
            }
            $this->_line++;
        } while ($this->valid());
    }

}
