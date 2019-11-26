<?php

namespace App\htmlBuilder;

use Framework\HtmlBuilder\Form as FrameworkForm;
use Framework\HtmlBuilder\HtmlElement;
use Framework\HtmlBuilder\Input;
use Framework\HtmlBuilder\Li;
use Framework\HtmlBuilder\Ul;




/**
 * This class generate 
 */
class Form extends FrameworkForm{

    function __construct(string $name, string $action = '#', string $method = 'POST',string $id = '') {
        parent::__construct($name, $action,  $method,$id);
        $this->addStyle('form');
        if(empty($id))
            $this->setId ('form');

        $this->sectionFields->addStyle('form-section');
        $this->sectionFields->addStyle('form-section-fields');

        $this->sectionButtons->addStyle('form-section');
        $this->sectionButtons->addStyle('form-section-buttons');
        
        $this->sectionErrors->addStyle('form-section');
        $this->sectionErrors->addStyle('form-section-errors');
    }

    public function addCancelButton($target, $textToPrint = 'Cancel'): self {
        $cancel = new Link($target, $textToPrint);
        $cancel->addStyle('button');
        $cancel->addStyle('form__button');
        $cancel->addStyle('form__cancel');
        $this->sectionButtons->addChild($cancel);
        $this->buttons['cancel'] = $cancel;
        return $this;
    }
    
    
    
    
    protected function getErrorDiv(Input $field, array $errors): HtmlElement {
        $div=new HtmlElement('div');
        $div->setId($field->id().'-errors');
        $div->addStyle('form-field-error');
        
        $ul=new Ul();
        foreach ($errors as $error)
        {
            if(!empty($error)){
                $li=new Li($error);
                $li->addstyle('form-field-error-message');
                $ul->addLi($li);
            }
        }
        $div->addChild($ul);
        return $div;
  
    }

}
