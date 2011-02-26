<?php

namespace Diggin\RobotRules\Parser;

/**
 * HtmlMeta Parse Client
 */
class HtmlMetaParser
{
    protected $adapter;

    public function fetchHTML($html)
    {
        return $this->getParserAdapter()->fetchHTML($html);
    }

    public function getParserAdapter()
    {
        if (!$this->adapter instanceof HtmlMeta\Adapter) {
            $this->adapter  = new HtmlMeta\Adapter\DOMDocumentParser;
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
