<?php
namespace App\Shared\Application;
/**
 * The base class for an Application Response
 *
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
     * Append one error like : field => value
     * @param string $field
     * @param string $error
     * @return \self
     */
    public function withError(string $error,string $field='') : self
    {
        if(empty($field)){
            $this->errors[]=$error;
        }
        else{
          $this->errors[$field]=$error;
        }
        return $this;
    }
    /**
     * Append one or many errors like : field => [...] 
     * If the field is not set then replace the current errors 
     * is not specified 
     * @param string $field
     * @param array $errors
     * @return \self
     */
    public function withErrors(array $errors,string $field='') :self
    {
        if(empty($field)){
            $this->errors=$errors;
        }
        else{ 
            $this->errors[$field]=$errors;
        }    
        return $this;
    }
}
