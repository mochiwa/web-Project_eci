<?php
namespace App\Article\Application;

use App\Article\Application\Poco\PaginationPoco;
use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Request\IndexRequest;
use App\Article\Application\Response\IndexResponse;
use App\Article\Infrastructure\Service\ArticlePaginatorService;
use App\Article\Model\Article\IArticleRepository;
use Framework\Paginator\Pagination;
use Framework\Paginator\Paginator;
use Framework\Router\IRouter;

/**
 * This application is responsible to get article list from the repository
 *
 * @author mochiwa
 */
class IndexApplication {

    /**
     * WHen the IndexRequest not specified the count of article per page
     * this value is used
     */
    const DEFAULT_MAX_ARTICLE_PER_PAGE = 8;

    /**
     * @var IArticleRepository 
     */
    private $repository;
    
    /**
     * @var IRouter
     */
    private $router;
    
    /**
     *
     * @var ArticlePaginatorService 
     */
    private $paginator;

    public function __construct(IArticleRepository $repository, IRouter $router) {
        $this->repository = $repository;
        $this->router=$router;
        $this->paginator=new ArticlePaginatorService($this->repository);
    }

    /**
     * Get a list of articles and build a pagination .
     * When article per page is not defined into the request user the self::DEFAULT_MAX_ARTICLE_PER_PAGE
     * When current page is not defined into the request , set the current page to 1
     * @param IndexRequest $request
     * @return IndexResponse
     */
    public function __invoke(IndexRequest $request): IndexResponse {
        $currentPage = $this->getCurrentPage($request);
        $maxArticlePerPage=$this->getArticlePerPage($request);
        
        $articles = $this->getArticles($currentPage,$maxArticlePerPage);
        $pagination=$this->paginator->getPagination($currentPage,$maxArticlePerPage);
        $paginationPoco=$this->buildPaginationPoco($pagination,$request->getIndexURL());

        return IndexResponse::of($paginationPoco, $articles);
    }
    
    /**
     * 
     * @param Paginator $paginator
     * @param int $currentPage
     * @return type
     */
    private function getArticles(int $currentPage,int $maxArticlePerPage){
        $articles = [];
        foreach ($this->paginator->getDataForPage($currentPage,$maxArticlePerPage) as $article) {
            $articles[] = ParkingPOCO::of($article);
        }
        return $articles;
    }
    
    
    /**
     * set the max article per page which paginator should generate.
     * if request hasn't it , then return the self::DEFAULT_MAX_ARTICLE_PER_PAGE
     * @param IndexRequest $request
     * @return int
     */
    private function getArticlePerPage(IndexRequest $request) {
        return $request->getArticlePerPage() ?? self::DEFAULT_MAX_ARTICLE_PER_PAGE;
        
    }
    
    /**
     * Return the current page from the request,
     * if request hasn't it , then return 1
     * @param IndexRequest $request
     * @return int
     */
    private function getCurrentPage(IndexRequest $request) : int{
        return $request->getCurrentPage() ?? 1;
    }
    
    /**
     * Build a pagination poco.
     * @param Pagination $pagination
     * @param string $url
     * @return type
     */
    private function buildPaginationPoco(Pagination $pagination,string $url):PaginationPoco{
        $pageLinks=[];
        foreach ($pagination->getPages() as $page) {
            $pageLinks[strval($page)]=$this->getPageLink($page,$url);
        }
        return PaginationPoco::of($pagination->getCurrentPage(),
                $pageLinks,
                $this->getPageLink($pagination->getPrevious(),$url),
                $this->getPageLink($pagination->getNext(),$url));
    }
    /**
     * Return a link to the page X at url index specified in the request
     * @param string $page
     * @param string $url
     * @return string
     */
    private function getPageLink(string $page,string $url): string
    {
        return $this->router->generateURL($url, ['action'=>'index','page'=>$page]);
    }

}
