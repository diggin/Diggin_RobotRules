<?php

namespace Diggin\RobotRules\Parser\HtmlMeta\Adapter;
use Diggin\RobotRules\Rules\HtmlMetaRobots;
use Diggin\RobotRules\Parser\HtmlMeta\Adapter\AbstractAdapter;

/**
 * A adapter parser for DOMDocument
 */
class DOMDocument extends AbstractAdapter
{
    private $dom;

    public function fetchHTML($html)
    {
        $dom = $this->getDomDocument();
        if (empty($html)) {
            throw new \InvalidArgumentException('empty string supplied as html');
        }

        $dom->loadHTML($html);

        return $this->fetchDOMDocument($dom);
    }

    public function fetchDOMDocument(\DOMDocument $dom)
    {
        $domXpath = new \DOMXPath($dom);
        $domXpath->registerNamespace("php", "http://php.net/xpath");
        $domXpath->registerPHPFunctions('strtolower');
        $domNodeList = $domXpath->query('//meta[php:functionString("strtolower", @name) = "robots"]');

        $robots = new HtmlMetaRobots;

        /** @var \DOMElement $domElement */
        foreach ($domNodeList as $domElement) {
            if ($domElement->hasAttribute('content')) {
                $contents = preg_split('#,#', $domElement->getAttribute('content'));
                foreach ($contents as $c) {
                    $robots->addContent(strtolower(trim($c)));
                }
            }
        }

        return $robots;
    }

    public function getDomDocument()
    {
        if (!$this->dom instanceof \DomDocument) {
               $this->dom = new \DomDocument('1.0');
               // $dom->validateOnParse = true; ?
        }

        return $this->dom;
    }
}
