<?php

class Diggin_RobotRules_Accepter implements Diggin_RobotRules_Accepter_AccepterInterface
{
    private $_protocols = array();

    public function __construct($useragent = null, $protocols = array())
    {
        $this->_useragent = $useragent;

        if (!is_array($protocols)) {
            $protocols = array($protocols);
        }

        $this->setProtocols($protocols);
    }

    public function addProtocol(Diggin_RobotRules_Protocol_ProtocolInteface $protocol)
    {
        $this->_protocols[] = $protocol;
    }

    public function isAllow($path = null)
    {

        $flag = false;
        foreach ($this->_protocols as $protocol) {

            $key = $protocol::ACCEPTER_KEY;

            $accepter = new {Diggin_RobotRules_Accepter.$key};

            //implement check
            //if (!$accepter->isImplement(__FUNCTION__)) break;
            
            $accepter->setAgent($this->_useragent);
            $accepter->setProtocol($protocol);

            if (!$accepter->isAllow($path)) {
                $flag = false;
            }
        }

        return $flag;
    }

    public function setProtocol(array $protocols)
    {
        $this->_protocols = $protocols;
    }
}
