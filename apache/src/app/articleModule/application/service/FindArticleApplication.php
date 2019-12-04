<?php

namespace App\Article\Application\Service;

use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\GettingArticleService;
use App\Article\Model\Article\Service\Request\GettingSingleArticleByIdRequest;
use Exception;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;

/**
 * Description of FindArticleApplication
 *
 * @author mochiwa
 */
class FindArticleApplication {
    private $session;
    private $repository;
    public function __construct(IArticleRepository $repository, SessionManager $session ) {
        $this->repository=$repository;
        $this->session=$session;
    }
    
    public function execute(string $articleId) : Response\FindResponse
    {
        try{
           $service = new GettingArticleService($this->repository); 
           $article = $service->execute(new GettingSingleArticleByIdRequest($articleId));
           return new Response\FindResponse([],[Dto\ArticleToForm::fromDomainResponse($article)]);
        } catch (Exception $ex) {
            $this->session->setFlash(FlashMessage::error($ex->getMessage()));
        }
        return new Response\FindResponse();
    }
}
