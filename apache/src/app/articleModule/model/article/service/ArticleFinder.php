<?php
namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\IArticleRepository;
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
    public function getFirst(): ?ArticleDomainResponse
    {
        if(isset($this->articleFound[0]))
            return new ArticleDomainResponse($this->articleFound[0]);
        return null;
    }
    /**
     * Return the last element found from finder
     * @return mixed
     */
    public function getLast(): ?ArticleDomainResponse
    {
        if(!empty($this->articleFound))
            return  new ArticleDomainResponse(end($this->articleFound));
        return null;
    }
    
    /**
     * Return all data found from finder
     * @return array
     */
    public function getArticles():array
    {
        return array_map(function($article){return new ArticleDomainResponse($article);},$this->articleFound);
    }
    
}
