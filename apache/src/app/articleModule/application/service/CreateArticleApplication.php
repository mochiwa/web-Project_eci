<?php
namespace App\Article\Application\Service;

use App\Article\Application\Service\Dto\ArticleToForm;
use App\Article\Application\Service\Response\CreatedResponse;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
use Framework\FileManager\FileUploader;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;

/**
 * This Application service is responsible 
 * to call the correct domain service for article creation
 * and to upload picture from the article
 *
 * @author mochiwa
 */
class CreateArticleApplication {
    private $repository;
    private $validator;
    private $uploader;
    private $session;
    
    public function __construct(IArticleRepository $repository , AbstractFormValidator $validator, FileUploader $uploader, SessionManager $session) {
        $this->repository=$repository;
        $this->validator=$validator;
        $this->uploader=$uploader;
        $this->session=$session;
    }
    
    
    public function execute(array $post): CreatedResponse {
        if (!$this->validator->validate($post)) {
            return $this->responseWithError($this->validator->getErrors(),$post);
        }
        
        try
        {
            $service=new CreateArticleService($this->repository);
            $domainresponse=$service->execute(CreateArticleRequest::fromArray($post));
            $this->uploader->uploadToDefault($post['picture'], $domainresponse->picture()->path());
        } catch (ArticleException $ex) {
            return $this->responseWithError([$ex->field()=>[$ex->getMessage()]],$post);
        }
        $this->session->setFlash(FlashMessage::success('The article "'.$domainresponse->title()->valueToString().'" has been created !'));
        return new CreatedResponse();
    }

    
    private function responseWithError(array $errors,array $postData)
    {
        $formData= Dto\ParkingView::fromPost($postData);
        return new CreatedResponse($errors, $formData);
    }

    
}
