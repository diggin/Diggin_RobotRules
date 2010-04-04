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
        if (is_string($uri)) {
            require_once 'Zend/Uri.php';
            ////if (!@parse_url) $path =
            $path = Zend_Uri::factory($uri)->getPath();
        } elseif (null === $uri) {
            if ($this->_useragent instanceof Zend_Http_Client) {
                $path = $this->_useragent->getUri()->getPath();
            } else {
                throw new Exception('$uri is not set');
            }
        } 


        if (!$this->_protocol) {
            throw new Exception();
        }

        foreach ($this->_protocol as $record) {

            //record has some user-agents
            $useragents = $record['user-agent'];

            foreach ($useragents as &$u) $u = $u->getValue(); unset($u);

            if ( (!in_array($this->_useragent, $useragents)) and
                  (!in_array('*', $useragents))) continue;         

            //match check
            if ($d = $this->_matchCheck('disallow', $record, $path)) {
                if ($a = $this->_matchCheck('allow', $record, $path)) {
                    if ((count($d) > count($a))) {
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
        if (count($a) == count($b)) {
            return 0;
        }
        return (count($a) < count($b)) ? -1 : 1;
    }
    
    protected function _matchCheck($field, Diggin_RobotRules_Protocol_Txt_Record $record, $path)
    {
        if ($path == '/robots.txt') {
            return (boolean) !($field == 'disallow');
        }
        
        if (!isset($record[$field])) return false;
        
        $flag = array();
        
        $recfield = $record[$field];
        usort($recfield, array($this, '_sort'));

        foreach ($recfield as $line) {
            $value = $line->getValue();
            
            if (trim($value) === '' && $field == 'disallow') {
                return array();
            }

            if ($value === '/') {
                $flag = array('/');
                continue;
            }
            
            $vals = explode('/', $value);
            $paths = explode('/', $path);

            if (count($vals) > count($paths)) {
                $flag = ($flag) ? $flag : array(); 
                continue;
            }

            $vals = array_filter($vals);
            $paths = array_filter($paths);

            foreach ($vals as $k => $v) {
                if (!isset($paths[$k])) {
                    break;
                }

                // check is_encoded path (not strict..)
                $v = (stripos($v, '%') === false) ? urlencode($v) : $v;
                $p = (stripos($paths[$k], '%') === false) ? urlencode($paths[$k]): $paths[$k];

                if (preg_match('#'.$v.'#i', $p) > 0) {
                       
                    if (count(array_diff_assoc($vals, $paths)) == 0) {
                        $flag = $vals;
                    } else {
                        if (!$flag) $flag = $vals;
                    }
                } else {
                   if (count($flag) >= 1 and (count(array_diff_assoc($vals, $paths)) == 0)) $flag = $vals;
                }
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
}
