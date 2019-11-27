<?php

namespace Framework\HtmlBuilder;

/**
 * This class is responsible to generate basic link <a href="x">link</a>
 */
class Link extends HtmlElement {

    private $target;

    function __construct(String $target, string $text, array $styles = []) {
        parent::__construct('a');
        $this->setTarget($target);
        $this->setContent($text);
        
        foreach ($styles as $class => $isActived)
            $this->addStyle($class, $isActived);
    }
    
    /**
     * Set the target for the link
     * @param string $target
     * @return \self
     */
    protected function setTarget(string $target): self {
        $this->target = $target;
        $att = Attribute::of('href')->setContent($target);
        $this->addAttribute($att);
        return $this;
    }
    
    /**
     * @return The target
     */
    public function target(){
        return $this->target;
    }

}
