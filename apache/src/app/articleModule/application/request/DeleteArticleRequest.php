<?php

namespace App\Article\Application\Request;

/**
 * The request for delete article application
 *
 * @author mochiwa
 */
class DeleteArticleRequest {
    private $articleId;
    
    private function __construct(string $articleId) {
        $this->articleId = $articleId;
    }
    public static function of(string $articleId):self{
        return new self($articleId);
    }
    
    public function getArticleId() :string {
        return $this->articleId;
    }



    
}
