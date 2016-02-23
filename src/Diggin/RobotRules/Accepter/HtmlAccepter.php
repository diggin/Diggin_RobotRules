<?php
namespace Diggin\RobotRules\Accepter;

/**
 * @todo handle HtmlMeta and _rel
 *      or HtmlMetaAccepter and HtmlRelAccepter ???
 */
class HtmlAccepter
{
    /**
     * @var \Diggin\RobotRules\Rules\HtmlMetaRobots
     */
    private $rules;

    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    public function canIndex()
    {
        return (!$this->rules->hasContent('noindex') &&
                !$this->rules->hasContent('none'));
    }

    public function canFollow()
    {
        return (!$this->rules->hasContent('nofollow') &&
                !$this->rules->hasContent('none'));
    }

    public function canArchive()
    {
        return (!$this->rules->hasContent('noarchive') &&
                !$this->rules->hasContent('none'));
    }

    public function canServe()
    {
        return (!$this->rules->hasContent('serve') &&
                !$this->rules->hasContent('none'));
    }

    public function canImageindex()
    {
        return (!$this->rules->hasContent('noimageindex') &&
                !$this->rules->hasContent('none'));
    }

    public function canImageclick()
    {
        return (!$this->rules->hasContent('noimageclick') &&
                !$this->rules->hasContent('none'));
    }
}
