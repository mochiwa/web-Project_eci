<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Request\ShowArticleRequest;
use App\Article\Application\Service\Response\ShowArticleResponse;
use App\Article\Model\Article\Service\ArticleFinder;
use App\Article\Model\Article\Service\Finder\FindById;

/**
 * Description of ShowArticleApplication
 *
 * @author mochiwa
 */
class ShowArticleApplication {
    private $articleFinder;
    
    public function __construct(ArticleFinder $articleFinder){
        $this->articleFinder = $articleFinder;
    }
    
    public function __invoke(ShowArticleRequest $request) {
        $article=$this->articleFinder->findArticles(FindById::fromStringID($request->getArticleId()))->getFirst();
        if(!$article){
            return ShowArticleResponse::of()->withErrors(['domain'=>'article not found']);
        }
        
        return ShowArticleResponse::of()->withArticle(Dto\ParkingView::fromDomainResponse($article));
    }
    
    
    
    
}
