<?php
namespace Diggin\RobotRules\Parser;
use Diggin\RobotRules\Rules\Txt as TxtRules;
use Diggin\RobotRules\Rules\Txt\LineEntity as Line;
use Diggin\RobotRules\Rules\Txt\RecordEntity as Record;
use Diggin\RobotRules\Rules\TxtContainer;

class TxtStringParser
{
    protected $config = array(
        'space_as_separator' => false
    );

    public function __construct($config = null)
    {
        if ($config) $this->setConfig($config);
    }

    public function setConfig($config)
    {
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }
    }

    /**
     * Parse robots.txt string content
     * 
     * @param string $robotstxt
     * @return \Diggin\RobotRules\Rules\TxtContainer
     */
    public static function parse($robotstxt, $config = null)
    {
        if (!preg_match('!\w\s*:!s', $robotstxt)) {
            return new TxtContainer(array());
        }

        $static = new static($config);
        
        $robotstxts = static::_toArray($robotstxt);
        
        $records = array();
        $lineno = 0;
        $previous_line = false;
        
        do {
            
            $line = $static->parseLine($robotstxts[$lineno]);
            $lineno++;
            
            if (!$line) {
                if ($line === false) {
                    $previous_line = false;
                }
                continue;
            }
            
            if (is_array($line)) {
                $first_line = current($line);
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
            

            $lines = (is_array($line)) ? $line : array($line);
            foreach ($lines as $line) {
                if ('sitemap' !== $line->getField()) {
                    if (!isset($record)) $record = new Record;
                    $record->append($line);
                } else {
                    if (!isset($sitemap_record)) $sitemap_record = new Record;
                    $sitemap_record->append($line);
                }
            }
            
            $previous_line = $end_line;
        } while (count($robotstxts) > $lineno);
        
        // push last record
        if (isset($record)) $records[] = $record;
        if (isset($sitemap_record)) $records[] = $sitemap_record;
        
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
     * @return Line|array|null|false
     */ 
    public function parseLine($line)
    {        
        // start with comment?
        if (preg_match('!^\s*#!', $line)) {
            return null;    
        }

        preg_match('!\s*([^:]*):\s*([^#]*)\s*#*\s*([^\z]*)!i', 
                    $line, $match);

        // ignore unmatched txt line.
        if (count($match) < 2) {
            return false;
        }

        if ($this->config['space_as_separator']) {
            $values = preg_split('#\s+#', trim($match[2]));
        }

        if (isset($values) && count($values) > 1) {
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
