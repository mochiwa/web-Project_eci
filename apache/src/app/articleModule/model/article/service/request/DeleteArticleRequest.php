<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Model\Article\Service\Request;

/**
 * Description of DeleteArticleRequest
 *
 * @author mochiwa
 */
class DeleteArticleRequest {
    private $articleId;
    
    public function __construct(string $articleId) {
        $this->articleId=$articleId;
    }
    
    public function getArticleId():string
    {
        return $this->articleId;
    }
}
