<?php

namespace Framework\HtmlBuilder;

/**
 *  This class is responsible to generate a basic (or complex) input for a form
 */
class Input extends HtmlElement {

    private $name;
    private $type;
    private $value;

    function __construct(string $name, string $type = 'text', string $id = '', string $value = '', string $placeHolder = '', bool $required = false) {
        parent::__construct('input', true);
        $this->setName($name);
        $this->setId($id);
        $this->setType($type);
        $this->setValue($value);

        if (!empty(trim($placeHolder)))
            $this->addAttribute(Attribute::of('placeHolder')->setContent($placeHolder));
        if ($required)
            $this->addAttribute(Attribute::of('required')->setContent('true'));
    }

    /**
     * Set the name of the input to find it with the Post or Get method
     * @throws Exception If the name is empty
     * @param string $name name of the input
     */
    public function setName(string $name): self {
        if (empty(trim($name)))
            throw new \InvalidArgumentException("The name of an input cannot be empty !");

        $this->name = Attribute::of('name')->setContent($name);
        $this->addAttribute($this->name);
        return $this;
    }

    /**
     * The type of the input (email,text,password,...)
     * @throws Exception If the type is empty
     * @param string $type the type
     */
    public function setType(string $type): self {
        if (empty(trim($type)))
            throw new \InvalidArgumentException("The type of an input cannot be empty !");

        $this->type = Attribute::of('type')->setContent($type);
        $this->addAttribute($this->type);
        return $this;
    }

    /**
     * set the value of the input (useful for the submit for example)
     * @param string $value the value to print
     */
    public function setValue(string $value): self {
        $this->value = $value;
        $this->addAttribute(Attribute::of('value')->setContent($value));
        return $this;
    }

    /**
     * Return the value of attribute name
     * @return string
     */
    public function name(): string {
        return $this->name->value();
    }

}
