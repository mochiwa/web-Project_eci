<?php

namespace App\Article\Application\Response;

/**
 * The abstract Application response that whole app response
 * must extends
 * @author mochiwa
 */
abstract class AbstractApplicationResponse {
    /**
     * array of errors
     * @var array 
     */
    private $errors=[];
    
    
    protected function __construct($errors=[]) {
        $this->errors = $errors;
    }
    /**
     * return true if this response has not errors
     * @return bool
     */
    public function hasErrors():bool {
        return  !empty($this->errors);
    }

    /**
     * Return the list of error
     * @return array
     */
    public function getErrors():array {
        return $this->errors;
    }

    /**
     * Set the errors to the this response
     * @param type $errors
     * @return $this
     */
    public function withErrors(Array $errors) :self {
        $this->errors = $errors;
        return $this;
    }
    
}
