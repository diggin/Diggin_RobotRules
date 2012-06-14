<?php

namespace Diggin\RobotRules\Parser;

/**
 * HtmlMeta Parse Client
 */
class HtmlMeta
{
    protected $adapter;

    public function fetchHTML($html)
    {
        return $this->getAdapter()->fetchHTML($html);
    }

    public function getAdapter()
    {
        if (!$this->adapter instanceof HtmlMeta\Adapter) {
            $this->adapter  = new HtmlMeta\Adapter\DOMDocument;
        }

        return $this->adapter;
    }

    /* above @todo
     *
    public static function parse($context)
    {
        //if () url, htmlstring, responseobject 
    }

    public static function fetch($context)
    {
        //$context DOMDocument, others
    }
    */
}
