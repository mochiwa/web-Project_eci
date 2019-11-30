<?php
namespace App\Article\Application\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
use Framework\FileManager\FileUploader;
use Framework\Validator\AbstractFormValidator;

/**
 * This Application service is responsible 
 * to call the correct domain service for article creation
 * and to upload picture from the article
 *
 * @author mochiwa
 */
class ArticleCreationService {
    private $repository;
    private $validator;
    private $uploader;
    
    public function __construct(IArticleRepository $repository , AbstractFormValidator $validator, FileUploader $uploader) {
        $this->repository=$repository;
        $this->validator=$validator;
        $this->uploader=$uploader;
    }
    
    
    public function execute(array $post): array {
        if (!$this->validator->validate($post)) {
            return $this->validator->getErrors();
        }
        try
        {
            $service=new CreateArticleService($this->repository);
            $response=$service->execute(CreateArticleRequest::fromArray($post));
            $this->uploader->uploadToDefault($post['picture'], $response->getPicture());
        } catch (ArticleException $ex) {
            return [$ex->field()=>[$ex->getMessage()]];
        }
        return ['success'=>['flash'=>'The article "'.$response->getTitle().'" has been created !']];
    }


    
}
