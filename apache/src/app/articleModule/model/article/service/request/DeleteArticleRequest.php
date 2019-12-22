<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Model\Article\Service\Request;

use App\Article\Model\Article\ArticleId;

/**
 * Description of DeleteArticleRequest
 *
 * @author mochiwa
 */
class DeleteArticleRequest {
    
    /**
     *
     * @var ArticleId 
     */
    private $articleId;
    
    private function __construct(ArticleId $articleId) {
        $this->articleId=$articleId;
    }
    
    public static function of(ArticleId $id):self{
        return new self($id);
    }
    
    public function getArticleId(): ArticleId
    {
        return $this->articleId;
    }
}
