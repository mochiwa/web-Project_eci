<?php

namespace App\Article\Application\Service\Response;


/**
 * Description of EditResponse
 *
 * @author mochiwa
 */
class EditResponse extends AbstractApplicationResponse{
    private $article;
    
    public function __construct(array $errors=[], $article=null) {
        $this->errors = $errors;
        $this->article = $article;
    }
    

    public function withArticleView(\App\Article\Application\Service\Dto\ParkingView $article):self
    {
        $this->article=$article;
        return $this;
    }

    public function getArticle() {
        return $this->article;
    }
    
    public function toForm()
    {
        return $this->article->toForm();
    }


}
