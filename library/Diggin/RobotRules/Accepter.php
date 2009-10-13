<?php

class Diggin_RobotRules_Accepter implements Diggin_RobotRules_Accepter_AccepterInterface
{
    private $_protocols = array();

    public function addProtocol(Diggin_RobotRules_Protocol_ProtocolInteface $protocol)
    {
        $this->_protocols[] = $protocol;
    }

    public function isAllowed()
    {
        foreach ($this->_protocols as $key => $protocol) {

            $accepter = new {Diggin_RobotRules_Accepter.$key};
            

            //implement check
            //if (!$accepter->isImplement(__FUNCTION__)) break;
            
            $accepter->setAgent('test');
            $accepter->setProtocol($protocol);

            if (!$accepter->isAllowed()) {
                //this->reason = aaa
                return false;
            }
        }

        return true;
    }

    public function setProtocol($protocol)
    {
        //is_array()
    }
}
