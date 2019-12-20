<?php
namespace App\Article\Application;

use App\Article\Application\Poco\PaginationPoco;
use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Request\IndexRequest;
use App\Article\Application\Response\IndexResponse;
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
    const DEFAULT_MAX_ARTICLE_PER_PAGE = 2;

    /**
     * @var IArticleRepository 
     */
    private $repository;
    
    /**
     *
     * @var IRouter
     */
    private $router;

    public function __construct(IArticleRepository $repository, IRouter $router) {
        $this->repository = $repository;
        $this->router=$router;
    }

    /**
     * Get a list of articles and build a pagination .
     * When article per page is not defined into the request user the self::DEFAULT_MAX_ARTICLE_PER_PAGE
     * When current page is not defined into the request , set the current page to 1
     * @param IndexRequest $request
     * @return IndexResponse
     */
    public function __invoke(IndexRequest $request): IndexResponse {
        $articlePerPage = $request->getArticlePerPage() ?? self::DEFAULT_MAX_ARTICLE_PER_PAGE;
        $currrentPage = $request->getCurrentPage() ?? 1;

        $paginator = new Paginator($this->repository, $articlePerPage);
        $articles = [];
        foreach ($paginator->getDataForPage($currrentPage) as $article) {
            $articles[] = ParkingPOCO::of($article);
        }
        
        $pagination=$paginator->getPagination($currrentPage);
        
        
        $response = new IndexResponse($this->buildPaginationPoco($pagination,$request->getIndexURL()), $articles);
        return $response;
    }
    
    
    private function buildPaginationPoco(Pagination $pagination,string $url){
        $pageLinks=[];
        foreach ($pagination->getPages() as $key => $page) {
            $pageLinks[strval($page)]=$this->getPageLink($page,$url);
        }
        return PaginationPoco::of($pagination->getCurrentPage(),
                $pageLinks,
                $this->getPageLink($pagination->getPrevious(),$url),
                $this->getPageLink($pagination->getNext(),$url));
    }
    
    private function getPageLink(string $page,string $url): string
    {
        return $this->router->generateURL($url, ['action'=>'index','page'=>$page]);
    }

}
