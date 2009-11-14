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

            //array_walk($useragents, create_function('$v, $k', '$v->getValue();'));
            foreach ($useragents as &$u) $u = $u->getValue(); unset($u);

            if ( (!in_array($this->_useragent, $useragents)) and
                  (!in_array('*', $useragents))) continue;         

            //match
            if ($this->_matchDisallow($record, $path)) {
                return false;
            }
        }

        return true;
    }

    protected function _matchDisallow(Diggin_RobotRules_Protocol_Txt_Record $record, $path)
    {
        if(!isset($record['disallow'])) return false;

        foreach ($record['disallow'] as $line) {
            $disallow = $line->getValue();
            
            $dis = explode('/', $disallow);
            $paths = explode('/', $path);

            if (count($dis) > count($paths)) return false;

            //todoo, cut space-only

            foreach ($dis as $k => $v) {
                if (count($paths) === $k) {
                    break;
                }
                //$v = (stripos($v, '%') === false) ? rawurlencode($v) : $v;
                //if (version_compare(PHP_VERSION, '5.3.0') >= 0) $v = str_replace('~', '%7E', $v);
                $v = (stripos($v, '%') === false) ? urlencode($v) : $v;

                //$p = (stripos($paths[$k], '%') === false) ? rawurlencode($paths[$k]): $paths[$k];
                //if (version_compare(PHP_VERSION, '5.3.0') >= 0) $p = str_replace('~', '%7E', $p);
                $p = (stripos($paths[$k], '%') === false) ? urlencode($paths[$k]): $paths[$k];

                if (!(preg_match('#'.$v.'#i', $p) > 0)) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function _matchAllow($record, $path)
    {
        if(!isset($record['allow'])) return false;

        foreach ($record['allow'] as $line) {
            $allow = $line->getValue();
            if (preg_match('#'.$allow.'#', $path, $m)) {
                return true;
            }
        }

        return false;
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
