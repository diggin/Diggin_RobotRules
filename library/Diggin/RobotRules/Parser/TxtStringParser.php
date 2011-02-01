<?php
namespace Diggin\RobotRules\Parser;
use Diggin\RobotRules\Rules\Txt as TxtRules;
use Diggin\RobotRules\Rules\Txt\LineEntity as Line;
use Diggin\RobotRules\Rules\Txt\RecordEntity as Record;
use Diggin\RobotRules\Rules\TxtContainer;

class TxtStringParser
{
    private function __construct()
    {}

    /**
     * @return \Diggin\RobotRules\Rules\TxtContainer
     */
    public static function parse($robotstxt)
    {
        if (!preg_match('!\w\s*:!s', $robotstxt)) {
            return new TxtContainer(array());
        }
        
        $robotstxts = self::_toArray($robotstxt);
        
        $records = array();
        $lineno = 0;
        $previous_line = false;
        
        do {
            
            $line = self::parseLine($robotstxts[$lineno]);
            $lineno++;
            
            if (!$line) {
                $previous_line = false;
                continue;
            }
            
            if (is_array($line)) {
                $first_line = \current($line);
                $end_line = end($line);
            } else {
                $first_line = $line;
                $end_line = $line;
            }
            
            /**
             * check - Is this new record set?
             * @todo option
             */
            if (!($previous_line instanceof Line) && ('user-agent' === $first_line->getField())) {
                if (isset($record)) $records[] = $record; //push previous record
                $record = new Record();
            }
            
            if (is_array($line)) {
                foreach ($line as $v) { 
                    $record->append($v);
                }
            } else {
                $record->append($line);
            }
            
            //$previous_line = $line;
            $previous_line = $end_line;
        } while(count($robotstxts) > $lineno);
        
        // push last reocrd
        if (isset($record)) $records[] = $record;
        
        return new TxtContainer($records);
    }
    
    /**
     *
     * @param string $robotstxt
     * @return array
     */
    protected static function _toArray($robotstxt)
    {
        // normalize line
        $robotstxt = str_replace(chr(13).chr(10), chr(10), $robotstxt);

        // Formally as RFC draft, robots.txt file is written with CRLF
        // @see http://www.robotstxt.org/norobots-rfc.txt
        $robotstxt = str_replace(array(chr(10), chr(13)), chr(13).chr(10), $robotstxt);

        $robotstxt = explode(chr(13).chr(10), $robotstxt);

        return $robotstxt;
    }

    /**
     * parse a line
     *
     * @param string $line
     * @return Line|array|false
     */ 
    public static function parseLine($line)
    {        
        // @todo write unit-test
        // start with comment?
        if (preg_match('!^\s*#!', $line)) {
            return false;    
        }

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
}
