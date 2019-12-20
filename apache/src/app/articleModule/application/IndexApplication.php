<?php
namespace App\Article\Application;

use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Request\IndexRequest;
use App\Article\Application\Response\IndexResponse;
use App\Article\Model\Article\IArticleRepository;
use Framework\Paginator\Paginator;

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

    public function __construct(IArticleRepository $repository) {
        $this->repository = $repository;
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
        $response = new IndexResponse($paginator->getPagination($currrentPage), $articles);
        return $response;
    }

}
