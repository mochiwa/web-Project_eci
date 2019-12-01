<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Model\Article;

/**
 * Description of ArticleException
 *
 * @author mochiwa
 */
class ArticleException extends \RuntimeException{
    private $field;
    
    function __construct(string $field,string $message = "") {
        parent::__construct($message);
        $this->field=$field;
    }
    
    
    public function field():string{
        return $this->field;
    }
}
