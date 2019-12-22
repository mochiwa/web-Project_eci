<?php

namespace App\Article\Application;

use App\Article\Application\Request\ReadArticleRequest;
use App\Article\Application\Response\ArticleApplicationResponse;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Service\ArticleFinder;
use App\Article\Model\Article\Service\Finder\ArticleFinderException;
use App\Article\Model\Article\Service\Finder\IFinder;
use InvalidArgumentException;

/**
 * Description of ReadArticleApplication
 *
 * @author mochiwa
 */
class ReadArticleApplication extends AbstractArticleApplication {
    
    /**
     * @var ArticleFinder 
     */
    private $articleFinder;

    public function __construct(ArticleFinder $articleFinder) {
        $this->articleFinder = $articleFinder;
    }

    
    
    public function __invoke(ReadArticleRequest $request): ArticleApplicationResponse{
        try{
            $articleId=ArticleId::of($request->getArticleId());
            $finder=$request->getFinder();
            
            $article=$this->articleFinder->findArticles($finder->setSearchValue($articleId))->getFirstOrThrow();
            $this->parkingPOCO= Poco\ParkingPOCO::of($article);
        } catch (InvalidArgumentException $ex) {
            $this->errors=['domain'=>'an error occurs durring the process'];
        }catch(ArticleFinderException $ex){
            $this->errors=['domain'=>'The article not found'];
        }
        finally{
            return $this->buildResponse();
        }
    }
}
