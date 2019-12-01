<?php
namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
use App\Article\Model\Article\Service\Response\ArticleCreatedResponse;
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
    public function execute(CreateArticleRequest $request) : ArticleCreatedResponse
    {
        $title= Title::of($request->getTitle());
        $attributes=[];
        foreach ($request->getAttributes() as $key=>$value) {
            $attributes[]=Attribute::of($key,$value);
        }
        $description=$request->getDescription();
        
        
        if($this->repository->isArticleTitleExist($title)){
            throw new ArticleException('title','An article with the title "'.$title->valueToString().'" already exist');
        }
        
        $articleId=$this->repository->nextId();
        $picture=$this->generatePictureName($articleId,$title);
        
        $article=Article::newArticle($articleId,$title,$picture,$attributes,$description);
        
        $this->repository->addArticle($article);
        return new ArticleCreatedResponse($picture->path(),$title->valueToString());
    }
    
    private function generatePictureName(ArticleId $articleId,Title $title):Picture
    {
        return Picture::of('article-'.$title->valueToString().'-'.$articleId->idToString());
    }
    
}