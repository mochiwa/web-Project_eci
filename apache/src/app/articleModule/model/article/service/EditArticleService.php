<?php

namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use App\Article\Model\Article\Title;

/**
 * Description of EditArticleService
 *
 * @author mochiwa
 */
class EditArticleService {
    private $repository;
    function __construct(IArticleRepository $repository)
    {
        $this->repository=$repository;
    }
    
    /**
     * Update the content of an article
     * @param EditArticleRequest $request
     * @throws EntityNotFoundException
     * @throws ArticleException
     */
    public function exectue(EditArticleRequest $request)
    {
        $articleId=ArticleId::of($request->getArticleId());
        if(!$this->repository->isArticleIdExist($articleId)){
            throw new EntityNotFoundException("The article with id=".$articleId->idToString().' not found in repository');
        }
        
        $editedArticle=$this->builEditedArticle($request);
        
        if($this->isTitleAlreadyUsed($editedArticle->title(),$editedArticle->id())){
            throw new ArticleException('The title "'.$editedArticle->title()->valueToString().'" is already used');
        }
        
        $this->repository->update($editedArticle);
    }
    
    /**
     * check if the title of an article is not used by another article.
     * 
     * @param Title $title
     * @param ArticleId $id
     * @return bool false if title is free or same as original , false any else
     */
    private function isTitleAlreadyUsed(Title $title,ArticleId $id):bool
    {
       if($this->repository->isArticleTitleExist($title))
        {
           if($id != $this->repository->findByTitle($title)->id()){
                return true;
           } 
        } 
        return false;
    }
    /**
     * Build the article updated
     * @param EditArticleRequest $request
     * @return Article
     */    
    private function builEditedArticle(EditArticleRequest $request): Article
    {
        $articleId= ArticleId::of($request->getArticleId());
        $title= Title::of($request->getTitle());
        $picture=Picture::of($request->getPicture());
        $attributes=[];
        foreach ($request->getAttributes() as $key=>$value) {
            $attributes[]= Attribute::of($key,$value);
        }
        $description=$request->getDescription();
        $orignal=$this->repository->findById($articleId);
        return Article::fromUpdate($articleId, $title, $picture, $attributes, $description, $orignal->creationDate());
    }
    
    
    
    
}
