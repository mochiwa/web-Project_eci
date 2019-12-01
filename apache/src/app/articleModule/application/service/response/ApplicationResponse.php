<?php
namespace App\Article\Application\Service\Response;


/**
 * Description of ApplicationResponse
 *
 * @author mochiwa
 */
class ApplicationResponse {
    private $errors=[];
    private $article=null;
    
    
    public function __construct($errors=[], $article=null) {
        $this->errors = $errors;
        $this->article = $article;
    }
    
    public function hasErrors() {
        return  !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }



    public function getArticle() {
        return $this->article;
    }



    public function withErrors($errors) {
        $this->errors = $errors;
        return $this;
    }

    public function withArticle($article) {
        $this->article = $article;
        return $this;
    }



}
