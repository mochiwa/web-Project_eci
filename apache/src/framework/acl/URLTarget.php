<?php

namespace Framework\Acl;

/**
 * This class represent a target as an url
 * for example if url contain the word admin or user-something
 * It can also contain the name of the route like admin.article
 *
 * @author mochiwa
 */
class URLTarget extends AbstractTarget{
    
    
    
    protected function __construct(string $url){
        parent::__construct($url);
    }

    /**
     * Just use the abstract Comparison
     * @param \Framework\Acl\AbstractTarget $target
     * @return bool
     */
    public function isMatch(AbstractTarget $target) :bool {
        return $this->abstractComparison($target);
    }

    /**
     * AbstractTarget is comparable only if it's an instance of self
     * @param \Framework\Acl\AbstractTarget $target
     * @return bool
     */
    public function isComparable(AbstractTarget $target): bool {
        return $target instanceof self;
    }

}
