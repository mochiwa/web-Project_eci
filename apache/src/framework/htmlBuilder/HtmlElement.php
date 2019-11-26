<?php

namespace Framework\HtmlBuilder;

use \InvalidArgumentException;

/**
 * Create  element dynamically
 */
class HtmlElement {

    /**
     * tag name like 'body' 'ul' 'div' ... 
     * @var string 
     */
    private $tagName;
    
    /**
     * List of attribute like 'style="..."' 'name=".."'
     * @var array 
     */
    private $attributes;
    /**
     * the content between open and close tag
     * @var string 
     */
    private $content;
    /**
     * other HtmlElement between open and close tag
     * @var array  
     */
    private $children;
    /**
     * if the element doesn't need end tag like '<input />' '<br>'...
     * @var bool 
     */
    private $inLineElement;

    function __construct(string $tagName, bool $inLineElement = false) {
        $this->tagName = $this->removeSpecialCharacter($tagName);
        $this->attributes = array();
        $this->content = "";
        $this->children = array();
        $this->inLineElement = $inLineElement;
    }

    public static function inLine(string $name): self {
        return new HtmlElement($name, true);
    }

    /**
     * Build a output in HTML format
     * @return string the element into an HTML readable way
     */
    public function toHtml(): string {
        $output = '<' . $this->tagName . ' ';
        foreach ($this->attributes as $attribute) {
            $output .= $attribute->toHtml() . ' ';
        }
        
        if($this->inLineElement)
            return rtrim($output).'>';

        $output[-1] = '>';
        foreach ($this->children as $child)
            $output .= $child->toHtml();
        $output .= $this->content;
        $output .= '</' . $this->tagName . '>';
        return $output;
    }

    /**
     * Append an attribute to the HTML element
     * @param  Attribute The attribute to append
     * @return self
     */
    public function addAttribute(Attribute $attribute): self {
        if (!$this->hasAttribute($attribute->name()))
            $this->attributes[$attribute->name()] = $attribute;
        return $this;
    }

    /**
     * Offer a way to append style (class in HTML) to the element,
     * If the class attribute is already present then append the content,
     * not replace.
     * @param string $class    the class name
     * @param string $isActive the argument to enable/disable value, can be closure
     */
    public function addStyle(string $class,$isActive=null ): self {
        if (!$this->hasAttribute('class'))
            $this->addAttribute(Attribute::of('class'));
        $this->attributes['class']->addContent($class, $isActive ?? true);
        return $this;
    }

    /**
     * Offer a way to append an id (id in HTML) to the element,
     * If the attribute is already present then replace the content,
     * @param string $value    the id value
     * @param bool $isActive the argument to enable/disable value, can be closure
     */
    public function setId(string $id) {
        if(empty(trim($id)))
            return;
        if (!$this->hasAttribute('id'))
            $this->addAttribute(Attribute::of('id'));
        $this->attributes['id']->setContent($id);
        return $this;
    }
    public function id(){
        if($this->hasAttribute('id'))
            return $this->attributes['id']->value();
        return null;
    }

    private function removeSpecialCharacter(string $str): string {
        return preg_replace('/[^a-z\-]/', '', strtolower($str));
    }

    /**
     * Set the content between tag if it's a text
     * example <p> the content setted </p>
     * @param string the content between <tag></tag>
     */
    public function setContent(string $content) : self {
        $this->content = htmlentities($content);
        return $this;
    }
    
    public function content():string 
    {
        return $this->content;
    }

    /**
     * Append an HtmlElement between tag like a child
     * @param HtmlElement The element to append
     */
    public function addChild(HtmlElement $child) :self {
        array_push($this->children, $child);
        return $this;
    }
    
    /**
     * Append an HtmlElement between tag like a child at the top
     * @param HtmlElement The element to append
     */
    public function addChildOnTop(HtmlElement $child) :self {
        array_unshift($this->children, $child);
        return $this;
    }

    /**
     * Return an HTML element contain between end and close tag
     * if not found return null 
     * @param string $id
     * @return type
     */
    public function getChild(string $id){
        foreach ($this->children as $child) {
            if($child->id()===$id)
                return $child;
        }
        return null;
    }
    /**
     * @param  string name of the attribute
     * @return boolean true if find , else any else
     */
    public function hasAttribute(string $name): bool {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Search among its attributes  , if found return it any else
     * throw an invalidArgumentException
     * 
     * @param  string The name of the attribute
     * @return Attribute The attribute found
     */
    public function getAttribute(string $name): Attribute {
        if (!$this->hasAttribute($name))
            throw new InvalidArgumentException('Element ' . $this->tagName . ' has not the attribute ' . $name);
        return $this->attributes[$name];
    }

}

?>