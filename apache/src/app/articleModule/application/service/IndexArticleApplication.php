<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Dto\ParkingView;
use App\Article\Application\Service\Response\IndexResponse;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\Response\ArticleDomainResponse;
use Framework\Paginator\Paginator;

/**
 * List all article found in repository and make a pagination of them.
 * Return an IndexResponse
 * 
 *
 * @author mochiwa
 */
class IndexArticleApplication {
    private $repository;
    public function __construct(IArticleRepository $repository) {
        $this->repository=$repository;
    }
    
    
    public function execute(int $page,int $articlePerPage=5): IndexResponse {
        
        $paginator=new Paginator($this->repository,$articlePerPage);
        $data=[];
        foreach ($paginator->getDataForPage(intval($page)) as $article) {
            $data[] = ParkingView::fromDomainResponse(new ArticleDomainResponse($article)); // ArticleView::fromDomainResponse(new ArticleDomainResponse($article));//a remplacer par le finder in dd
        }
        $response = new IndexResponse($paginator->getPagination($page),$data);
        return $response;
    }
}
