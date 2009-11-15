<?php
require_once 'Zend/Uri.php';

class Diggin_RobotRules_Accepter_Txt 
    implements Diggin_RobotRules_Accepter_AccepterInterface
{

    /** Diggin_RobotRules_Protocol_Txt*/
    private $_protocol;
    /** string or Zend_Http_Client */
    private $_useragent;

    public function isAllow($uri = null)
    {
        if (is_string($uri)) {
            ////if (!@parse_url) $path =
            $path = Zend_Uri::factory($uri)->getPath();
        } elseif (!$uri and ($this->_useragent instanceof Zend_Http_Client)) {
            $path = $this->_useragent->getUri()->getPath();
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

            //if ($this->_matchCheck('disallow', $record, $path)) var_dump($record['disallow'], $path);
            //match check
            if ($d = $this->_matchCheck('disallow', $record, $path)) {
                //var_dump('--', $d);
                if ($a = $this->_matchCheck('allow', $record, $path)) {
                    //var_dump($d, $a);
                    if (($d === true) ? 1 : $d  >= $a) {
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
        }

        return true;
    }

    protected function _matchCheck($field, Diggin_RobotRules_Protocol_Txt_Record $record, $path)
    {
        if(!isset($record[$field])) return false;

        $flag = false;
        foreach ($record[$field] as $line) {
            $value = $line->getValue();

            if ($value === '/') {
                $flag = ($flag !== true) ? $flag : true; 
                //$flag = 1; 
                //$flag = ($flag !== false and $flag !== 1) ? $flag : 1; 
                //var_dump("aa", $flag);
                continue;
            }
            
            $vals = explode('/', $value);
            $paths = explode('/', $path);

            if (count($vals) > count($paths)) {
                $flag = false; continue;
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
                    //$flag = true;
                    $flag = ((int)$flag < count($vals))? count($vals): $flag;
                } else {
                    $flag = false;
                }
            }
        }

        return $flag;
    }

    public function setProtocol($protocol)
    {
        if (!($protocol instanceof Diggin_RobotRules_Protocol_Txt)) {
            throw new Exception();
        }

        $this->_protocol = $protocol;
    }

    public function setUserAgent($useragent)
    {
        $this->_useragent = $useragent;
    }
}
