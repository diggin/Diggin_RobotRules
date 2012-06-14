<?php
namespace Diggin\RobotRules\Rules;

class HtmlMetaRobots 
{
    private $contens = array();

    //private $config = array(
    //    'discrepancy' => false
    //);

    public function __construct($contens = array())
    {
        $this->contents = (array) $contens;
    }

    public function hasContent($key)
    {
        $search = array_search($key, $this->contents);

        return (false !== $search) ? true : false;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function addContent($key)
    {
        $this->contents[] = $key;
    }
}
