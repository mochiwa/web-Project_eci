<?php
namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\IArticleRepository;

/**
 * Service domain for the creation of an article
 *
 * @author mochiwa
 */
class CreateArticleService {
    
    /**
     * @var IArticleRepository  
     */
    private $repository;
    
    public function __construct(IArticleRepository $articleRepository) {
        $this->repository=$articleRepository;
    }
   
    /**
     * Take a CreateArticleRequest to create a new article
     * @param CreateArticleRequest $request
     * @return Article
     */
    public function execute(CreateArticleRequest $request): Article
    {
        $article= Article::newArticle($this->repository->nextId(),
                $request->getTitle(),
                $request->getPicture(),
                $request->getAttributes(),
                $request->getDescription());
        
        $this->repository->addArticle($article);
        return $article;
    }
}
