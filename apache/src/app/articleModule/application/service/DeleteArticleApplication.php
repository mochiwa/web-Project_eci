<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Response\DeleteResponse;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\DeleteArticleService;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use TheSeer\Tokenizer\Exception;

/**
 * This application service is responsible to delete
 * an article
 *
 * @author mochiwa
 */
class DeleteArticleApplication {
    private $repository;
    private $sesion;
    public function __construct(IArticleRepository $repository, SessionManager $session ) {
        $this->repository=$repository;
        $this->sesion=$session;
    }
    
    public function execute(string $articleId) : Response\DeleteResponse
    {
        try{
            $service = new DeleteArticleService($this->repository);
            $service->execute(new DeleteArticleRequest($articleId));
        } catch (\Exception $ex) {
            $this->sesion->setFlash(FlashMessage::error('This article has been already deleted'));
            return new DeleteResponse([$ex->getMessage()]);
        }
        $this->sesion->setFlash(FlashMessage::error('This article has been deleted'));
        return  new DeleteResponse();
    }
}
