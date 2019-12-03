<?php

namespace App\Article\Application\Service\Response;

use App\Article\Model\Article\Service\Response\ArticleViewResponse;

/**
 * Description of ArticleFormResponse
 *
 * @author mochiwa
 */
class ArticleFormResponse {
    private $data;
    private $id;
    public function __construct(ArticleViewResponse $article) {
        $this->id=$article->getId();
        $this->data=[
            'form-title'=>$article->getTitle(),
            'form-picture'=>$article->getPicture(),
            'form-city'=>$article->getAttributes()['city'],
            'form-place'=>$article->getAttributes()['place'],
            'form-name'=>$article->getAttributes()['name'],
            'form-description'=>$article->getDescription()
        ];
    }
    
    public function toForm():array
    {
        return $this->data;
    }
    
    public function getId()
    {
        return $this->id;
    }
}
