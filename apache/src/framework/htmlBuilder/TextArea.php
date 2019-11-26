<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\HtmlBuilder;

/**
 * Description of TextArea
 *
 * @author mochiwa
 */
class TextArea extends HtmlElement{
    
    function __construct(string $name,string $placeHolder = '',bool $required = false)
    {
        parent::__construct('textArea');
        $this->setName($name);
        if (!empty(trim($placeHolder))){
            $this->addAttribute(Attribute::of('placeHolder')->setContent($placeHolder));
        }
        if ($required){
            $this->addAttribute(Attribute::of('required')->setContent('true'));
        }
    }
    
     public function setName(string $name): self {
        if (empty(trim($name)))
            throw new \InvalidArgumentException("The name of an input cannot be empty !");

        $this->name = Attribute::of('name')->setContent($name);
        $this->addAttribute($this->name);
        return $this;
    }
    public function name(): string {
        return $this->name->value();
    }
}
