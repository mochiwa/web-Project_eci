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
class EditArticleApplication {
    private $repository;
    private $validator;
    
    public function __construct(IArticleRepository $repository , AbstractFormValidator $validator) {
        $this->repository=$repository;
        $this->validator=$validator;
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
        return $response->withFlashMessage('The article "'.$articleResposne->getTitle().'" has been updated !');
    }
}
