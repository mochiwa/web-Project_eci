<?php
namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\Finder\ArticleFinderException;
use App\Article\Model\Article\Service\Finder\IFinder;
use App\Article\Model\Article\Service\Response\ArticleDomainResponse;

/**
 * Description of ArticleFinder
 *
 * @author mochiwa
 */
class ArticleFinder {
    /**
     * the data found from finder
     * @var array 
     */
    private $articleFound;
    
    /**
     *
     * @var IArticleRepository 
     */
    private $repository;
    
    
    public function __construct(IArticleRepository $repository) {
        $this->repository=$repository;
        $this->articleFound=[];
    }

    /**
     * Execute the process of finding articles from finder
     * @param IFinder $finder
     * @return \self
     */
    public function findArticles(IFinder $finder) : self
    {
        $this->articleFound=call_user_func($finder,$this->repository);
        return $this;
    }


    /**
     * Return the first element found from finder
     * @return mixed
     */
    public function getFirst(): ?Article
    {
        if(isset($this->articleFound[0])){
            return $this->articleFound[0];
        }
        return null;
    }
    
    /**
     * Try to get the first article found , if no article foudn throw
     * ArticleFinderException
     * @return Article
     * @throws ArticleFinderException
     */
    public function getFirstOrThrow():Article{
        $article=$this->getFirst();
        if($article===null){
            throw new ArticleFinderException('No article found');
        }
        return $article;
    }


    /**
     * Return the last element found from finder
     * @return mixed
     */
    public function getLast(): ?Article
    {
        if(!empty($this->articleFound)){
            return end($this->articleFound);
        }
        return null;
    }
    
    /**
     * Return all data found from finder
     * @return array
     */
    public function getArticles():array
    {
        return array_map(function($article){return $article;},$this->articleFound);
    }
    
}
