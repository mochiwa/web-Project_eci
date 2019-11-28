<?php
namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
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
     * 
     * @throws ArticleException when an article with the same title already exist
     */
    public function execute(CreateArticleRequest $request)
    {
        $title= Title::of($request->getTitle());
        $picture=Picture::of($request->getPicture());
        $attributes=[];
        foreach ($request->getAttributes() as $key=>$value) {
            $attributes[]=Attribute::of($key,$value);
        }
        $description=$request->getDescription();
        
        
        
        if($this->repository->isArticleTitleExist($title)){
            throw new ArticleException('title','An article with the title "'.$title->valueToString().'" already exist');
        }
        
        $article=Article::newArticle($this->repository->nextId(),$title,$picture,$attributes,$description);
        
        $this->repository->addArticle($article);
    }
    
    
}
