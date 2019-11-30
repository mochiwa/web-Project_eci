<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Response\ApplicationResponse;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\GettingArticleService;
use App\Article\Model\Article\Service\Request\GettingSingleArticleByIdRequest;
use Exception;

/**
 * Description of FindArticleApplication
 *
 * @author mochiwa
 */
class FindArticleApplication {
    
    private $repository;
    public function __construct(IArticleRepository $repository ) {
        $this->repository=$repository;
    }
    
    public function execute(string $articleId) : ApplicationResponse
    {
        $response=new ApplicationResponse();
        try{
           $service = new GettingArticleService($this->repository); 
           $article = $service->execute(new GettingSingleArticleByIdRequest($articleId));
           $response->withArticle($article);
        } catch (Exception $ex) {
            return $response->withErrors([$ex])->withFlashMessage($ex->getMessage());
        }
        return $response;
    }
}
