<?php

namespace App\Article\Application\Request;

use App\Article\Model\Article\Service\Finder\FindById;
use App\Article\Model\Article\Service\Finder\IFinder;

/**
 * Request for ReadArticleApplication
 *
 * @author mochiwa
 */
class ReadArticleRequest {
    /**
     * @var String
     */
    private $articleId;
    
    /**
     * @var IFinder 
     */
    private $finder;

    private function __construct(String $articleId, IFinder $finder) {
        $this->articleId = $articleId;
        $this->finder=$finder;
    }
    
    public static function fromId(string $articleId):self{
        return new self($articleId, FindById::fromStringID($articleId));
    }
    
    public function getArticleId(): String {
        return $this->articleId;
    }
    
    public function getFinder(): IFinder {
        return $this->finder;
    }





    
}
