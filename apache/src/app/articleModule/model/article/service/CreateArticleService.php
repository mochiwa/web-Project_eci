<?php
namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\IArticleRepository;

/**
 * Description of CreateArticleService
 *
 * @author mochiwa
 */
class CreateArticleService {
    private $articleRepository;
    public function __construct(IArticleRepository $articleRepository) {
        $this->articleRepository=$articleRepository;
    }
   
    
    public function execute(CreateArticleRequest $request)
    {
        
        $article= Article::newArticle($this->articleRepository->nextId(),
                $request->getTitle(),
                $request->getPicture(),
                $request->getAttributes(),
                $request->getDescription());
        
        $this->articleRepository->addArticle($article);
        return $article;
    }
}
