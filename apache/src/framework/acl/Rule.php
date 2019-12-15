<?php

namespace Framework\Acl;

/**
 * Description of Rule
 *
 * @author mochiwa
 */
class Rule {
    /**
     *
     * @var target 
     */
    private $target;
    
    /**
     * set if it is an allowing or denied rule
     * @var bool 
     */
    private $isAllowed;
    
    
    public function __construct(AbstractTarget $target, bool $isAllowed) {
        $this->target = $target;
        $this->isAllowed=$isAllowed;
    }
    
    public static function Allow(AbstractTarget $target):self
    {
        return new self($target,true);
    }
    public static function Deny(AbstractTarget $target):self
    {
        return new self($target,false);
    }
    
    public static function Invert(Rule $rule):self{
        return new self($rule->getTarget(),!$rule->isAllowed());
    }

    public function getTarget(): AbstractTarget {
        return $this->target;
    }

    public function isAllowed() {
        return $this->isAllowed;
    }


}
