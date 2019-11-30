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
    private $flashMessage='';
    private $article=null;
    
    
    public function __construct($errors=[], $flashMessage='', Article $article=null) {
        $this->errors = $errors;
        $this->flashMessage = $flashMessage;
        $this->article = $article;
    }
    
    public function isError() {
        return  !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getInformation() :string {
        return $this->flashMessage;
    }

    public function getArticle() {
        return $this->article;
    }



    public function withErrors($errors) {
        $this->errors = $errors;
        return $this;
    }

    public function withFlashMessage($flashMessage) {
        $this->flashMessage = $flashMessage;
        return $this;
    }

    public function withArticle($article) {
        $this->article = $article;
        return $this;
    }



}
