<?php

class Diggin_RobotRules_Accepter implements Diggin_RobotRules_Accepter_AccepterInterface
{

    public function isAllow($path)
    {
        foreach ($this->getRegistry()->getAccepter() as $key => $accepter) 
        {
            $accepter->setProtocol($this->getRegistry()->loadProtocol($key));
            $accepter->isAllow();
        }
    }
}
