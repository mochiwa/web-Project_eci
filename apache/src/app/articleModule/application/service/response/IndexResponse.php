<?php

namespace App\Article\Application\Service\Response;

/**
 * This response deals with the index controlle ,
 * it returns a list of article and the count of page
 *
 * @author mochiwa
 */
class IndexResponse extends AbstractApplicationResponse{
    
    /**
     * contain the list of articles for this page
     * @var Array 
     */
    private $articles;
    
    /**
     * return the count of page
     * @var int
     */
    private $pageCount;
    
    public function __construct(array $articles=[], int $pageCount=0) {
        parent::__construct();
        $this->articles = $articles;
        $this->pageCount = $pageCount;
    }

    
    public function getArticles() : array {
        return $this->articles;
    }
    
    public function getPageCount() : int {
        return $this->pageCount;
    }


    
}
