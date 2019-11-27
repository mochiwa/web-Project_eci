<?php
namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Title;

/**
 * Domain service for the creation of an article
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
        $title= Title::of($request->getTitle());
        $picture=Picture::of($request->getPicture());
        $attributes=[];
        foreach ($request->getAttributes() as $key=>$value) {
            $attributes[]= Attribute::of($key,$value);
        }
        $description=$request->getDescription();
        
        $article= Article::newArticle($this->repository->nextId(),
                $title,$picture,$attributes,$description);
        
        $this->repository->addArticle($article);
        return $article;
    }
}
