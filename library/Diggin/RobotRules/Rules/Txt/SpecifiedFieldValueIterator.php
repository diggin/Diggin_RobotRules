<?php

namespace Diggin\RobotRules\Rules\Txt;

class SpecifiedFieldValueIterator extends \FilterIterator
{
    private $pos = false;
    private $fieldKey = '';
    private $fieldnum = 0;

    public static function factory(\Diggin\RobotRules\Rules\Txt $txt, $field)
    {
        $specifiedFieldValueIterator = new self($txt);
        $specifiedFieldValueIterator->setFieldKey($field);
        $specifiedFieldValueIterator->rewind();

        return $specifiedFieldValueIterator;
    }

    public function setFieldKey($key)
    {
        $this->fieldKey = $key;
    }
    
    public function current()
    {
        // Don't call without rewind
        //if (!$this->accept()) {return false;}

        $fieldArray = $this->getInnerIterator()->current()->offsetGet($this->fieldKey);
        
        return $fieldArray[(int)$this->pos]->getValue();
    }

    public function next()
    {
        if ($this->pos === false) {
            $fieldArray = $this->getInnerIterator()->current()->offsetGet($this->fieldKey);
            $this->fieldnum = count($fieldArray);
            $this->pos = 1;
        } else if ($this->pos < $this->fieldnum){
            $this->pos++;
        }
        
        if ($this->fieldnum === ($this->pos)) {
            $this->getInnerIterator()->next();
            $this->pos = false;
        }
    }

    public function accept()
    {
        return $this->getInnerIterator()->current()->offsetExists($this->fieldKey);
    }

    public function valid()
    {
        return ($this->getInnerIterator()->current() instanceof \Diggin\RobotRules\Rules\Txt\RecordEntity);
    }
}
