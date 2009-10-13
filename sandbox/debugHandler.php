<?php
/** Diggin_RobotRules_Protocol_Txt **/
require_once 'RobotRules/Protocol/Txt.php';
/** Diggin_RobotRules_Protocol_Txt_Line **/
require_once 'RobotRules/Protocol/Txt/Line.php';
/** Diggin_RobotRules_Protocol_Txt_Record **/
require_once 'RobotRules/Protocol/Txt/Record.php';

if (debug_backtrace()) return;

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

$robots = new Diggin_RobotRules_Protocol_Txt($robots);

class Diggin_RobotRules_Accepter
{}

interface Diggin_RobotRules_Accept_Adapter_Adaptable{}
interface Diggin_RobotRules_Accept_Executable {
    public function execute($context);
}

//class Diggin_RobotRules_Reader_Txt_Record
class Diggin_RobotRules_Reader_TxtRecord
    implements Diggin_RobotRules_Accept_Executable
{
    public function execute(Diggin_RobotRules_Accept_Txt_Record_FieldArray $fields)
    {
        
    }
}

class Diggin_RobotRules_Reader_Txt_Record_ListCommand
{
    public function execute($fields)
    {
        // 1 allow
        //   $checked_allow = true or false
        // 2 disallow
        
        $checkedAllow = false;
        foreach ($fields as $line) {
            //$this->path
            
        }        
    }
}

class Diggin_RobotRules_Accept_Txt_Record_Adapter_Standard
{
}
/**
class Diggin_RobotRules_Accept_Txt_Record_FieldArray
{
    function __construct(array $fields)
    {}

    function next()
    {
        $this->_index++;
    }
    
    function getCurrentLine()
    {
        return $this->_fields[$this->index];
    }
}




