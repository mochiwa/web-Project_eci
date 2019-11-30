<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Response\ApplicationResponse;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\DeleteArticleService;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest;

/**
 * Description of DeleteArticleApplication
 *
 * @author mochiwa
 */
class DeleteArticleApplication {
    private $repository;
    public function __construct(IArticleRepository $repository ) {
        $this->repository=$repository;
    }
    
    public function execute(string $articleId) : ApplicationResponse
    {
        $response=new ApplicationResponse();
        try{
            $service = new DeleteArticleService($this->repository);
            $service->execute(new DeleteArticleRequest($articleId));
        } catch (\Exception $ex) {
            return $response->withFlashMessage('This article has been already deleted');
        }
        return $response->withFlashMessage('The article has been deleted !');
    }
}
