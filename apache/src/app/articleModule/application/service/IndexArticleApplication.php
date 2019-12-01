<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Response\ApplicationResponse;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\Response\ArticleViewResponse;
use App\Shared\Paginator;

/**
 * Description of IndexArticleApplication
 *
 * @author mochiwa
 */
class IndexArticleApplication {
    private $repository;
    public function __construct(IArticleRepository $repository) {
        $this->repository=$repository;
    }
    
    
    public function execute($page,int $articlePerPage=10): ApplicationResponse {
        $response = new ApplicationResponse();
        $paginator=new Paginator($this->repository,$articlePerPage);
        $data=[];
        foreach ($paginator->getDataForPage(intval($page)===0 ? 1 : $page) as $article) {
            $data[] = new ArticleViewResponse($article);
        }
        
        return $response->withArticle($data);
    }
}
