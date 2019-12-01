<?php

namespace App\Article\Model\Article\Service\Request;

/**
 * Description of GettingSingleArticleByIdRequest
 *
 * @author mochiwa
 */
class GettingSingleArticleByIdRequest {
    private $articleId;
    
    public function __construct(string $articleId) {
        $this->articleId = $articleId;
    }
    
    public function getArticleId() {
        return $this->articleId;
    }



    
}
