<?php

namespace App\Article\Application\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use App\Article\Model\Article\Service\Response\ArticleViewResponse;
use Framework\Validator\AbstractFormValidator;

/**
 * Description of ArticleEditionService
 *
 * @author mochiwa
 */
class ArticleEditionService {
    private $repository;
    private $validator;
    
    public function __construct(IArticleRepository $repository , AbstractFormValidator $validator) {
        $this->repository=$repository;
        $this->validator=$validator;
    }
    
    
    public function execute(array $post): array {
        if (!$this->validator->validate($post)) {
            $article=$this->repository->findById(ArticleId::of($post['id']));
            $status=['article'=>new ArticleViewResponse($article),'errors'=>$this->validator->getErrors()];
            return $status;
        }
        try
        {
            $service=new EditArticleService($this->repository);
            $response=$service->execute(EditArticleRequest::fromArray($post));
        } catch (ArticleException $ex) {
            return [$ex->field()=>[$ex->getMessage()]];
        }
        return ['success'=>['flash'=>'The article "'.$response->getTitle().'" has been updated !']];
    }
}
