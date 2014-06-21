<?php
namespace Diggin\RobotRules\Accepter;

use Diggin\RobotRules\Rules\Txt as TxtRules;
use Diggin\RobotRules\Rules\Txt\RecordEntity as Record;
use Diggin\RobotRules\Exception;

class TxtAccepter
{
    /**
     * @var Diggin\RobotRules\Rules\Txt
     */
    private $rules;

    private $useragent;

    public function isAllow($uri = null)
    {
        if (is_string($uri)) {
            if (preg_match('#^http#', $uri)) {
                $path = $this->fetchPathFromUri($uri);
            } else {
                $path = $uri;
            }
        } else {
            throw new Exception\InvalidArgumentException('$uri is not set');
        }

        if (!$this->rules) {
            throw new Exception\LogicException('robots.txt rule is not specified. Use setRules()');
        }

        // flag 
        $allow = true;
        
        foreach ($this->rules as $k => $record) {

            // checking field-set has user-agent
            if (isset($record['user-agent'])) {
                $useragents = $record['user-agent'];
            } else {
                continue;
            }

            //record has some user-agents
            foreach ($useragents as &$u) $u = $u->getValue(); unset($u);

            if (in_array($this->getUserAgent(), $useragents)) {
                $ua = $this->getUserAgent();
            } else if (in_array('*', $useragents)) {
                if (isset($ua)) continue;
            } else {
                continue;
            }

            //match check
            if ($d = $this->matchCheck('disallow', $record, $path)) {
                if ($a = $this->matchCheck('allow', $record, $path)) {
                    if (strlen($d) > strlen($a)) {
                        $allow = false;
                        continue;
                    }
                    $allow = true;
                    continue;
                } else {
                    $allow = false;
                    continue;
                }
            }
        }

        return $allow;
    }
    
    private function _sort($a, $b)
    {
        return (strlen($a->getValue()) < strlen($b->getValue())) ? -1 : 1;
    }
    
    protected function matchCheck($field, Record $record, $path)
    {
        if ($path == '/robots.txt') {
            return (boolean) !($field == 'disallow');
        }
        
        if (!isset($record[$field])) return false;
        
        $flag = false;
        
        $recfield = $record[$field];
        usort($recfield, array($this, '_sort'));

        foreach ($recfield as $line) {
            $value = $line->getValue();

            if ($value === '') continue;
            
            if ($value === '/') {
                if ($path === '/') return true;
            }
            
            // "/" character, which has special meaning in a path.
            $value = self::urldecodeWithSlashAsSpecial($value);
            $path = self::urldecodeWithSlashAsSpecial($path);

            // @todo unescape '?' '*'

            // https://developers.google.com/webmasters/control-crawl-index/docs/robots_txt 
            // $ designates the end of the URL
            $value_tmp = preg_replace('#^([^$]+\$?).*$#','$1',$value);

            if (preg_match('#^'. str_replace(array('\*','\$'), array('.*','$'), preg_quote($value_tmp)) . '#', $path)) {
                $flag = $value;
            }
        }

        return $flag;
    }

    public function setRules(TxtRules $rules)
    {
        $this->rules = $rules;
    }

    public function setUserAgent($useragent)
    {
        $this->useragent = $useragent;
    }

    public function getUserAgent()
    {
        return $this->useragent;
    }

    public static function urldecodeWithSlashAsSpecial($path)
    {
        return preg_replace_callback('/((?!(%2f)).)+/i',
                              function($v){return rawurldecode($v[0]);},
                              $path);
    }

    /**
     * @todo enable using Zend\Uri, Zend_Uri, Net_URL2 for strict parsing uri
     */
    protected function fetchPathFromUri($uri)
    {
        $parse_url = parse_url($uri);
        return $parse_url['path'];
    }
}
