<?php
namespace App\Article\Application\Service\Request;
/**
 * This is the request to the application layer
 * to see an article
 *
 * @author mochiwa
 */
class ShowArticleRequest {
    /**
     *
     * @var string 
     */
    private $articleId;
    
    public function __construct($articleId) {
        $this->articleId = $articleId;
    }
    
    /**
     * 
     * @return string
     */
    public function getArticleId() : string {
        return $this->articleId;
    }



    
}
