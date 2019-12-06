<?php

namespace App\Article\Model\Article\Service\Finder;

use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
/**
 * Find an article from the repository that match with the id
 *
 * @author mochiwa
 */
class FindById implements IFinder{
    /**
     *
     * @var IArticleRepository 
     */
    private $articleId;
    
    private function __construct(ArticleId $articleId) {
        $this->articleId=$articleId;
    }
    
    /**
     * 
     * @param string $id
     * @return \self
     */
    public static function fromStringID(string $id) : self
    {
        return new self(ArticleId::of($id));
    }
    /**
     * 
     * @param ArticleId $id
     * @return \self
     */
    public static function fromArticleId(ArticleId $id) :self
    {
        return new self($id);
    }
    
    /**
     * Return an array with the article that match with the id passed into the constructor
     * If EntityNotFoundException is throw then return an empty array
     * @param IArticleRepository $repository
     * @return array
     */
    public function __invoke(IArticleRepository $repository): array {
        $articles=[];
        try{
            $articles[]=$repository->findById($this->articleId);
        } catch (EntityNotFoundException $ex) {
            
        }   
        return $articles;
    }

}
