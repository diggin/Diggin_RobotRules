<?php

namespace Diggin\RobotRules\Parser\HtmlMeta;

interface Adapter
{
    /**
     * @param string
     */
    public function fetchHTML($html);

}
