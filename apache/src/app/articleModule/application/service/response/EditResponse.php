<?php

namespace App\Article\Application\Service\Response;

use App\Article\Application\Service\Dto\ArticleToForm;
use App\Article\Application\Service\Dto\ArticleView;

/**
 * Description of EditResponse
 *
 * @author mochiwa
 */
class EditResponse extends AbstractApplicationResponse{
    private $article;
    
    public function __construct(array $errors=[],ArticleView $article=null) {
        $this->errors = $errors;
        $this->article = $article;
    }
    

    public function withArticleView(ArticleView $article):self
    {
        $this->article=$article;
        return $this;
    }

    public function getArticle() {
        return $this->article;
    }
    
    public function getArticleToForm()
    {
        return ArticleToForm::fromArticleView($this->article);
    }


}
