<?php

set_include_path(dirname(dirname(__FILE__)) . '/library' . PATH_SEPARATOR . get_include_path());
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);


$robots = <<<EOF
User-agent: Google
User-agent: Infoseek
Disallow:

User-Agent: Googlebot
Disallow: /cgi-bin/
Disallow: /*.gif$

User-agent: *
Disallow: /     

EOF;

$protocol = new Diggin_RobotRules_Protocol_Txt($robots);

class Diggin_RobotRules_Accept_Txt_UserAgentSearchFilter extends FilterIterator
{

    private $_useragent = "";
    
    public function __construct(Diggin_RobotRules_Protocol_Txt $protocol, $useragent = '*')
    {
        parent::__construct($protocol);
        $this->_useragent = $useragent;   
    }

    public function accept()
    {
        $record = $this->current();

       //todoo handle if none user-agent
       //if (!array_key_exists('User-Agent', $record))
        foreach ($record['user-agent'] as $ua)
        {
            if ($this->_useragent === $ua->getValue() or
                '*' === $ua->getValue()) {
                return true;
            }
        }

        return false;
    }
}


foreach (new Diggin_RobotRules_Accept_Txt_UserAgentSearchFilter($protocol, 'Google') as $key => $record)
{
    print_r($record);
}
