<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Dto\ParkingView;
use App\Article\Application\Service\Response\EditResponse;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Model\Article\Service\GettingArticleService;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use App\Article\Model\Article\Service\Request\GettingSingleArticleByIdRequest;
use Framework\Session\FlashMessage;
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
    
    
    public function execute(array $post): EditResponse {
        $response=new EditResponse();
        
        if (!$this->validator->validate($post)) {
            $service=new GettingArticleService($this->repository);
            $domainResponse=$service->execute(new GettingSingleArticleByIdRequest($post['id']));
            return $response->withErrors($this->validator->getErrors())->withArticleView(ParkingView::fromDomainResponse($domainResponse));
        }
        
        try
        {
            $service=new EditArticleService($this->repository);
            $domainResponse=$service->execute(EditArticleRequest::fromArray($post));
        } catch (ArticleException $ex) {
            return $response->withErrors([$ex->field()=>[$ex->field()]])
                    ->withArticleView(ParkingView::fromPost($post));
        }
        $this->session->setFlash(FlashMessage::success('The article "'.$domainResponse->title()->valueToString().'" has been updated !'));
        return $response->withArticleView(ParkingView::fromDomainResponse($domainResponse));
    }
}
