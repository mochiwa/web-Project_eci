<?php

namespace App\Article\Application\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use App\Article\Model\Article\Service\Response\ArticleViewResponse;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;

/**
 * Description of ArticleEditionService
 *
 * @author mochiwa
 */
class EditArticleApplication {
    private $repository;
    private $validator;
    private $session;
    
    public function __construct(IArticleRepository $repository , AbstractFormValidator $validator, SessionManager $session) {
        $this->repository=$repository;
        $this->validator=$validator;
        $this->session=$session;
    }
    
    
    public function execute(array $post): Response\ApplicationResponse {
        $response=new Response\ApplicationResponse();
        $article=$this->repository->findById(ArticleId::of($post['id']));
        
        if (!$this->validator->validate($post)) {
            return $response->withArticle(new ArticleViewResponse($article))
                    ->withErrors($this->validator->getErrors());
        }
        try
        {
            $service=new EditArticleService($this->repository);
            $articleResposne=$service->execute(EditArticleRequest::fromArray($post));
        } catch (ArticleException $ex) {
            return $response->withErrors([$ex->field()=>[$ex->getMessage()]])
                    ->withArticle(new ArticleViewResponse($article));
        }
        $this->session->setFlash(\Framework\Session\FlashMessage::success('The article "'.$articleResposne->getTitle().'" has been updated !'));
        return $response->withFlashMessage('The article "'.$articleResposne->getTitle().'" has been updated !');
    }
}
