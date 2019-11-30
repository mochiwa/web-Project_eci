<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Response\ApplicationResponse;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\DeleteArticleService;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use TheSeer\Tokenizer\Exception;

/**
 * Description of DeleteArticleApplication
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
    
    public function execute(string $articleId) : ApplicationResponse
    {
        $response=new ApplicationResponse();
        try{
            $service = new DeleteArticleService($this->repository);
            $service->execute(new DeleteArticleRequest($articleId));
        } catch (Exception $ex) {
            $this->sesion->setFlash(FlashMessage::error('This article has been already deleted'));
            return $response->withFlashMessage('This article has been already deleted');
        }
        $this->sesion->setFlash(FlashMessage::error('This article has been deleted'));
        return $response->withFlashMessage('The article has been deleted !');
    }
}
