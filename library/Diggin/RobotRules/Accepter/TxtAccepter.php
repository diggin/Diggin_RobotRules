<?php

namespace Diggin\RobotRules\Accepter;
use Diggin\RobotRules\Accepter;
use Diggin\RobotRules\Rules\Txt as TxtRules;
use Diggin\RobotRules\Rules\Txt\RecordEntity as Record;

class TxtAccepter implements Accepter
{
    /**
     * @var Diggin\RobotRules\Rules\Txt
     */
    private $rules;

    private $_useragent;

    private $_reason;

    public function isAllow($uri = null)
    {
        if ($uri instanceof \Zend_Uri_Http) {
            $path = $uri->getPath();
        } else if (is_string($uri)) {
            if (preg_match('#^http#', $uri)) {
                require_once 'Zend/Uri.php';
                ////if (!@parse_url) $path =
                $path = \Zend_Uri::factory($uri)->getPath();
            } else {
                $path = $uri;
                //$path = trim($uri, '/');
            }
        } else if (null === $uri && $this->_useragent instanceof \Zend_Http_Client) {
            $path = $this->getUserAgent()->getUri()->getPath();
        } else {
            throw new \Exception('$uri is not set');
        }

        if (!$this->rules) {
            throw new \Exception();
        }

        foreach ($this->rules as $k => $record) {

            //record has some user-agents
            // checking field set has user-agent
            if (isset($record['user-agent'])) {
                $useragents = $record['user-agent'];
            } else {
                continue;
            }

            foreach ($useragents as &$u) $u = $u->getValue(); unset($u);

            if ( (!in_array($this->_useragent, $useragents)) and
                  (!in_array('*', $useragents))) continue;         

            //match check
            if ($d = $this->_matchCheck('disallow', $record, $path)) {
                if ($a = $this->_matchCheck('allow', $record, $path)) {
                    if (strlen($d) > strlen($a)) {
                        //$this->get
                        return false;
                    }
                    return true;
                } else {
                    return false;
                }
            }

            break;
        }

        return true;
    }
    
    private function _sort($a, $b)
    {
        return (strlen($a->getValue()) < strlen($b->getValue())) ? -1 : 1;
    }
    
    protected function _matchCheck($field, Record $record, $path)
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
                //continue;
            }
            
            $value = urldecode($value);
            $path = urldecode($path);

            if (preg_match('#^'. preg_quote($value) . '#', $path)) {
                $flag = $value;
            }
        }

        return $flag;
    }

    public function setRules($rules)
    {
        // treat as robots.txt string or file
        if (is_string($rules)) {
            // @todo handle robots.txt as filepass

            $rules = new Diggin\RobotRules\Parser\TxtParser($rules);
        }

        if (!($rules instanceof TxtRules)) {
            throw new \Exception();
        }

        $this->rules = $rules;
    }

    public function setUserAgent($useragent)
    {
        $this->_useragent = $useragent;
    }

    public function getUserAgent()
    {
        return $this->_useragent;
    }

}
