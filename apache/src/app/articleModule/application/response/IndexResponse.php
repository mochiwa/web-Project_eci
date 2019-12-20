<?php
namespace App\Article\Application\Response;

use App\Article\Application\Poco\PaginationPoco;
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
    
    protected function __construct(PaginationPoco $pagination,array $articles) {
        parent::__construct();
        $this->articles = $articles;
        $this->pagination = $pagination;
    }
    
    public static function of(PaginationPoco $pagination,array $articles=[]): self{
        return new self($pagination,$articles);
    }
    
    public function getArticles() : array {
        return $this->articles;
    }
    
    public function getPagination() : PaginationPoco {
        return $this->pagination;
    }


    
}
