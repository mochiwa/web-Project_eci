<?php
namespace Framework\HtmlBuilder;

/**
 * Description of List
 *
 * @author mochiwa
 */
class Ul extends HtmlElement{
    
    function __construct()
    {
        parent::__construct('ul');
    }
    
    public function addLi($li): self
    {
        $this->addChild($li);
        return $this;
    }
}

class Li extends HtmlElement{
    
    function __construct(string $content='')
    {
        parent::__construct('li');
        $this->setContent($content);
    }
}
