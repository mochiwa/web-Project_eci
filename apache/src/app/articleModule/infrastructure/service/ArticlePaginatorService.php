<?php
namespace App\Article\Infrastructure\Service;

use App\Article\Model\Article\IArticleRepository;
use Framework\Paginator\IPaginatorService;
use Framework\Paginator\Pagination;
use InvalidArgumentException;

/**
 * Description of ParkingArticlePaginator
 *
 * @author mochiwa
 */
class ArticlePaginatorService implements IPaginatorService{
    /**
     *
     * @var IArticleRepository
     */
    private $articleRepository;
    
    
    public function __construct(IArticleRepository $repository) {
        $this->articleRepository=$repository;
    }
    
    public function getDataForPage(int $currentPage, int $articlePerPage): array {
        if($articlePerPage<=0 || $currentPage<=0 || $this->pageCount($articlePerPage)<$currentPage){
            return [];
        }
        
        $firstIndex= ($currentPage-1) * $articlePerPage;
        return $this->articleRepository->setOfArticles($firstIndex, $articlePerPage);
                
    }
    
    /**
     * Return number of article per page at rank+1
     * 
     * @param int $articlePerPage
     * @return int
     */
    public function pageCount(int $articlePerPage):int{
        $page= $this->articleRepository->sizeof() / $articlePerPage;
        
        return $page === 0 ? 1 : ceil($page);
    }

    /**
     * Return a pagination with actual data
     * @param int $currentPage
     * @return Pagination
     */
    public function getPagination(int $currentPage,int $articlePerPage):Pagination
    {
        return (new Pagination())
                ->setTotalPageCount($this->pageCount($articlePerPage))
                ->setLimitToGenerate(9)
                ->setCurrent($currentPage);
    }
    
}
