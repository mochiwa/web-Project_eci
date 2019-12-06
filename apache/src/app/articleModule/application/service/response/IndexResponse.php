<?php

namespace App\Article\Application\Service\Response;

use Framework\Paginator\Pagination;

/**
 * This response deals with the index controller ,
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
     * the pagination of the index
     * @var Pagination
     */
    private $pagination;
    
    public function __construct(Pagination $pagination,array $articles=[]) {
        parent::__construct();
        $this->articles = $articles;
        $this->pagination = $pagination;
    }

    
    public function getArticles() : array {
        return $this->articles;
    }
    
    public function getPagination() : Pagination {
        return $this->pagination;
    }


    
}
