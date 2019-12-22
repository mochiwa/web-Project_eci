<?php


namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest;

/**
 * This domain service is responsible to remove an article from the 
 * repository
 *
 * @author mochiwa
 */
class DeleteArticleService {
    private $repository;
    
    public function __construct(IArticleRepository $repository) {
        $this->repository=$repository;
    }
    
    /**
     * Remove the article if it is found else throw ArticleException
     * @param DeleteArticleRequest $request
     * @throws ArticleException
     * @return Article The Article deleted
     */
    public function __invoke(DeleteArticleRequest $request){
        $articleId=$request->getArticleId();
        try{
            $article=$this->repository->findById($articleId);
            $this->repository->removeArticle($articleId);
            return $article;
        } catch (\Exception $ex) {
            throw new ArticleException('id', 'The article with id :'.$articleId->idToString().' Not found');
        }
        
    }
}
