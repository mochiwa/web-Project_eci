<?php
namespace App\Article\Application\Service;

use App\Article\Application\Service\Response\ApplicationResponse;
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
    
    
    public function execute(array $post): ApplicationResponse {
        $response = new ApplicationResponse();
        if (!$this->validator->validate($post)) {
            return $response->withErrors($this->validator->getErrors());
        }
        try
        {
            $service=new CreateArticleService($this->repository);
            $articleResponse=$service->execute(CreateArticleRequest::fromArray($post));
            $this->uploader->uploadToDefault($post['picture'], $articleResponse->getPicture());
        } catch (ArticleException $ex) {
            return $response->withErrors([$ex->field()=>[$ex->getMessage()]]);
        }
        $this->session->setFlash(FlashMessage::success('The article "'.$articleResponse->getTitle().'" has been created !'));
        return $response;
    }


    
}
