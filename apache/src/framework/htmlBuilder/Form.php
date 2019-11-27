<?php

namespace Framework\HtmlBuilder;

/**
 * This class is responsible to create a basic form
 * with name , action, id and method
 */
class Form extends HtmlElement {

    protected $sectionFields;
    protected $fields;
    protected $sectionButtons;
    protected $buttons;
    protected $sectionErrors;

    /**
     * The base argument to build a form , if the Id is specified then
     * An id is automatically generated for the fields and buttons section like
     * $id-section-fields for example
     * 
     * @param string $name   The name of the form
     * @param string $action the action of the form
     * @param string $method the method used , default is POST
     * @param string $id     the id, can be empty
     */
    function __construct(string $name, string $action = '#', string $method = 'POST', string $id = '') {
        parent::__construct('form');
        $this->sectionErrors = new HtmlElement('div');
        $this->sectionFields = new HtmlElement('div');
        $this->sectionButtons = new HtmlElement('div');
        $this->fields=[];
        $this->buttons=[];
        
        
        $this->setName($name);
        $this->setId($id);
        $this->setAction($action);
        $this->setMethod(strtoupper($method));
        
        
        $this->addChild($this->sectionFields);
        $this->addChild($this->sectionButtons);
    }
    
    protected function setName(string $name)
    {
        if(empty(trim($name)))
            throw new \InvalidArgumentException();
        $this->addAttribute(Attribute::of('name')->setContent($name));
    }
    protected function setAction(string $action)
    {
        if(empty(trim($action)))
            throw new \InvalidArgumentException();
        $this->addAttribute(Attribute::of('action')->setContent($action));
    }
    protected function setMethod(string $method)
    {
        if($method!=='GET' && $method !=='POST')
            throw new \InvalidArgumentException();
        $this->addAttribute(Attribute::of('method')->setContent($method));
    }
    public function setId(string $id) {
        parent::setId($id);
        if($this->id()!==$id)
            return ;
        $this->sectionErrors->setId($id.'-section-errors');
        $this->sectionFields->setId($id.'-section-fields');
        $this->sectionButtons->setId($id.'-section-buttons');
    }

    

    /**
     * Append an input element to the sectionFields
     * @param Input $input The input element to append
     */
    public function addInput(HtmlElement $input): self {
        $this->sectionFields->addChild($input);
       
        if(!$input->hasAttribute('id'))
            $input->setId($this->generateFormId($input->name()));
        $this->fields[$input->id()] = $input;
        return $this;
    }
    /**
     * Generate a unique id ( in the form area fields) , the id will be like idform-field-nameOfInput
     * or idform-field-nameOfInput-n if id is already used
     * @param type $inputName
     * @return string
     */
    private function generateFormId($inputName):string
    {
        $id=$this->id() ?? 'form';
        $id.='-field-'.$inputName;
        $baseId=$id;
        $count=2;
        while($this->isInputIdAlreadyUsed($id))
            $id=$baseId.'-'.$count++;
        return $id;
        
    }
   
    /**
     * Check if id already
     * @param type $id
     * @return bool
     */
    private function isInputIdAlreadyUsed($id):bool
    {
        foreach ($this->fields as $input)
            if($input->id()===$id)
                return true;
        return false;
    }
    
    /** 
     * Return an input from fields that has same id
     * @param string $id
     */
    public function getInput(string $id)
    {
        foreach ($this->fields as $input) {
            if($input->id()===$id)
                return $input;
        }
        return null;
    }

    /**
     * Append a button into the sectionButton 
     * @param HtmlElement $button [description]
     */
    public function addButton(HtmlElement $button): self {
        $this->sectionButtons->addChild($button);
        $this->buttons[$button->name()] = $button;
        return $this;
    }
    
    /**
     * Set errors for each field
     * @param array $errors list of errors like [name => []]
     */
    public function setErrors(Array $errors = []) {
        if(empty($errors))
            return;
        foreach ($this->fields as $field)
            if (isset($errors[$field->name()]))
                 $this->sectionErrors->addChild($this->getErrorDiv($field, $errors[$field->name()]));
        $this->addChildOnTop($this->sectionErrors);
    }

    /**
     * Return a div with errors for a field
     * @param \Core\HtmlElementBuilder\Input $field field where error occurs
     * @param array $errors list of errors
     * @return \Core\HtmlElementBuilder\HtmlElement the div
     */
    protected function getErrorDiv(HtmlElement $field,array $errors) : HtmlElement
    {
        $div=new HtmlElement('div');
        $div->setId($field->id().'-errors');
        $ul=new Ul();
        foreach ($errors as $error)
            $ul->addLi(new Li($error));
        $div->addChild($ul);
        return $div;
    }
}