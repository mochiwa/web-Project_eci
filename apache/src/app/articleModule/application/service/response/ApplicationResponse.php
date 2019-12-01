<?php
namespace App\Article\Application\Service\Response;

use App\Article\Model\Article\Article;

/**
 * Description of ApplicationResponse
 *
 * @author mochiwa
 */
class ApplicationResponse {
    private $errors=[];
    private $article=null;
    
    
    public function __construct($errors=[], Article $article=null) {
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
