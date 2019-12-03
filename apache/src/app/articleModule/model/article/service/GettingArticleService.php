<?php

namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\Request\GettingSingleArticleByIdRequest;

/**
 * Description of GettingArticleService
 *
 * @author mochiwa
 */
class GettingArticleService {
    /**
     * @var IArticleRepository
     */
    private $repository;
    
    public function __construct(IArticleRepository $repository) {
        $this->repository=$repository;
    }
    
    public function execute(GettingSingleArticleByIdRequest $request) : Response\ArticleDomainResponse
    {
        $articleId=ArticleId::of($request->getArticleId());
        if(!$this->repository->isArticleIdExist($articleId)){
            throw new EntityNotFoundException ('The Article with id "'.$articleId->idToString().'"Not found in repository');
        }
        $article=$this->repository->findById($articleId);
        return new Response\ArticleDomainResponse($article);
    }
    
}
