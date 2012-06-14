<?php

namespace Diggin\RobotRules\Parser\HtmlMeta\Adapter;
use Diggin\RobotRules\Parser\HtmlMeta\Adapter;

abstract class AbstractAdapter implements Adapter
{
    /**
     * a utility method.
     */
    public static function parseHtml($html)
    {
        $static = new static();
        $rule = $static->fetchHTML($html);

        return $rule;
    }
}
