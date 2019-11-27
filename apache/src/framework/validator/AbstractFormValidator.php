<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\Validator;

/**
 * The AbstractFormValidator use Valitron
 * to validate form
 *
 * @author mochiwa
 */
abstract class AbstractFormValidator implements IValidator{
    /**
     * The concrete validator
     * @var \Valitron\Validator
     */
    protected $validator;
    /**
     *
     * @var array list of error like field=>[error,..]
     */
    protected $errors;
    
    /**
     * Form data like field => value
     * @param type $data
     */
    function __construct(array $data)
    {
        $this->validator=new \Valitron\Validator($data);
        $this->errors=[];
        $this->initRules();
    }

    /**
     * Return the list of errors like field => [error,...]
     * @return array 
     */
    public function getErrors(): array {
        $errors=$this->validator->errors();
        foreach ($errors as $field => $error){
            $this->errors[$field]=$error;
        }
        return $this->errors;
    }
    
    /**
     * Return true if validator return true
     * @return bool
     */
    public function isValid(): bool {
        return $this->validator->validate();
    }
    
    /**
     * Where child will put rules
     */
    public abstract function initRules();

}
