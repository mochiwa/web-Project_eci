<?php

namespace Framework\Html;

/**
 * Description of Form
 *
 * @author mochiwa
 */
class Form extends HtmlTag{
    protected $inputs;
    public function __construct(string $action='#',string $method='POST',string $id='form') {
        parent::__construct('form');
        $this->inputs=[];
        $this->setId($id);
        $this->setAttribute(Attribute::oneContent('action', $action));
        $this->setAttribute(Attribute::oneContent('method', $method));
    }
    
    /**
     * Append an input into the form
     * @param \Framework\Html\Input $input
     * @return \self
     */
    public function addInput(Input $input) : self
    {
        $uniqueInput=$this->generateUniqueInput($input);
        $this->inputs[$uniqueInput->getId()]=$uniqueInput;
        $this->addChild($uniqueInput);
        return $this;
    }
    
    /**
     * Generate an input with an unique Id
     * @param self $input
     * @return \Framework\Html\Input
     */
    private function generateUniqueInput(Input $input) : Input
    {
        if(!$input->hasAttribute('id'))
        {
            $input->setId($this->htmlId.'-'.$input->getName());
        }
        $input->setId($this->generateUniqueId($input->getId()));
        return $input;
    }
    
    /**
     * Generate an unique id for the form, if the Id already exist
     * then append an hypen and increment a number like
     * myId-1  , myId-2 ,...
     * @param string $baseId
     * @return string
     */
    private function generateUniqueId(string $baseId):string
    {
        if(key_exists($baseId, $this->inputs))
        {
            $count=2;
            while(key_exists($baseId.'-'.$count, $this->inputs))
            {
                $count++;
            }
            return $baseId.'-'.$count;
        }
        return $baseId;
    }
    
    
    /**
     * Return the input with the id,if input not found return null
     * @param string $id
     * @return \Framework\Html\Input|null
     */
    public function getInputById(string $id): ?Input
    {
        if(key_exists($id, $this->inputs)){
            return $this->inputs[$id];
        }
        return null;
    }
    
    /**
     * Append an input with its label
     * @param \Framework\Html\Input $input
     * @param string $labelValue
     */
    public function addInputWithLabel(Input $input,string $labelValue):self
    {
        $uniqueInput=$this->generateUniqueInput($input);
        $this->addChild($this->makeLabel($labelValue, $uniqueInput->getId()));
        $this->addInput($uniqueInput);
        return $this;
    }
    
    /**
     * Make a label for an input in form
     * @param string $value between label tag
     * @param string $inputId to append at attribute 'for'
     * @return \Framework\Html\HtmlTag
     */
    protected function makeLabel(string $value,string $inputId): HtmlTag
    {
        $label=HtmlTag::make('label');
        $label->addAttribute(Attribute::oneContent('for', $inputId));
        $label->addText($value);
        return $label;
    }
    
    /**
     * Create a div positioned on top that
     * will contain all error on from form
     * @param array $errors
     * @return \self
     */
    public function setErrors(array $errors=[]):self
    {
        if(empty($errors)){
            return $this;
        }
        
        $div= HtmlTag::make('div');
        foreach ($errors as $fieldErrors) {
            $div->addChild($this->makeListOfError($fieldErrors));
        }
      $this->addChildOnTop($div);
      return $this;
    }
    
    /**
     * Return a list (ul and li ) with errors 
     * @param array $errors
     * @return HtmlTag
     */
    protected function makeListOfError(array $errors): HtmlTag
    {
        $ul = HtmlTag::make('ul');
        foreach ($errors as $error) {
            $ul->addChild(HtmlTag::make('li')->addText($error));
        }
        return $ul;
    }
   
    /**
     * Set a value for each field
     * @param array $values
     */
    public function fillForm(array $values=[])
    {
        foreach ($values as $fieldId=>$value) {
            if(array_key_exists($fieldId, $this->inputs)){
                $pos = array_search($this->inputs[$fieldId], $this->children);
                $this->children[$pos]->setValue($value);
            }
        }
    }
  

}
