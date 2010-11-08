<?php

class Diggin_RobotRules_Accepter_Txt 
    implements Diggin_RobotRules_Accepter_AccepterInterface
{
    /** Diggin_RobotRules_Protocol_Txt*/
    private $_protocol;
    /** string or Zend_Http_Client */
    private $_useragent;

    private $_reason;

    public function isAllow($uri = null)
    {
        if ($uri instanceof Zend_Uri_Http) {
            $path = $uri->getPath();
        } else if (is_string($uri)) {
            if (preg_match('#^http#', $uri)) {
                require_once 'Zend/Uri.php';
                ////if (!@parse_url) $path =
                $path = Zend_Uri::factory($uri)->getPath();
            } else {
                $path = $uri;
                //$path = trim($uri, '/');
            }
        } else if (null === $uri && $this->_useragent instanceof Zend_Http_Client) {
            $path = $this->_useragent->getUri()->getPath();
        } else {
            throw new Exception('$uri is not set');
        }

        if (!$this->_protocol) {
            throw new Exception();
        }

        foreach ($this->_protocol as $k => $record) {

            //record has some user-agents
            $useragents = $record['user-agent'];

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
            } else {
                if ($this->_matchCheck('allow', $record, $path)) {
                    return true;
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
    
    protected function _matchCheck($field, Diggin_RobotRules_Protocol_Txt_Record $record, $path)
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

    public function setProtocol($protocol)
    {
        if (!($protocol instanceof Diggin_RobotRules_Protocol_Txt)) {
            require_once 'Diggin/RobotRules/Accepter/Exception.php';
            throw new Exception();
        }

        $this->_protocol = $protocol;
    }

    public function setUserAgent($useragent)
    {
        $this->_useragent = $useragent;
    }

    public function getUserAgent($useragent)
    {
        return $this->_useragent;
    }
}
