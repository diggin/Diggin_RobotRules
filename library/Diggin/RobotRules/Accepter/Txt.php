<?php
require_once 'Zend/Uri.php';

class Diggin_RobotRules_Accepter_Txt 
    implements Diggin_RobotRules_Accepter_AccepterInterface
{

    /** Diggin_RobotRules_Protocol_Txt*/
    private $_protocol;
    /** string or Zend_Http_Client */
    private $_useragent;
    /** Zend_Uri */
    private $_uri;


    public function isAllow($uri = null)
    {
        if (is_string($uri)) {
            $uri = Zend_Uri::factory($uri);
        } elseif (!$uri and ($this->_useragent instanceof Zend_Http_Client)) {
            $uri = $this->_useragent->getUri();
        }

        if (!($uri instanceof Zend_Uri_Http)) {
            throw new Exception();
        }

        if (!$this->_protocol) {
            throw new Exception();
        }

        foreach ($this->_protocol as $record) {

            //match
            foreach($record['user-agent'] as $ua) {
                if (($this->_useragent === $ua->getValue()) or 
                    ('*' === $ua->getValue()) ) {
                    
                    if ($this->_isDisallow($record, $uri)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    protected function _isDisallow($record, Zend_Uri $uri)
    {
        foreach ($record['disallow'] as $line) {
            $path = $line->getValue();
            if (preg_match('#'.$path.'#', 
                           $uri->getPath(), $m)) {
                var_dump((string)$uri, $m);
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

    //set target
    public function setUri($url)
    {
        if (is_string($url)) {
            $url = Zend_Uri::factory($url);
        }

        //if (!instanceof)

        $this->_uri = $url;
    }

    public function setUserAgent($useragent)
    {
        $this->_useragent = $useragent;
    }
    

}
