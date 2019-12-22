<?php

use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\ReadArticleApplication;
use App\Article\Application\Request\ReadArticleRequest;
use App\Article\Model\Article\Service\ArticleFinder;
use App\Article\Model\Article\Service\Finder\ArticleFinderException;
use App\Article\Model\Article\Service\Finder\IFinder;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;

/**
 * Description of ReadArticleApplication
 *
 * @author mochiwa
 */
class ReadArticleApplicationTest extends TestCase {

    private $articleFinder;
    private $finder;
    private $app;

    protected function setUp() {
        $this->articleFinder = $this->createMock(ArticleFinder::class);
        $this->finder=$this->createMock(IFinder::class);
        $this->app = new ReadArticleApplication($this->articleFinder,$this->finder);
    }

    function test_invoke_shouldReturnApplicationResponseWithError_whenArticleIdIsEmpty() {
        $request = $this->createMock(ReadArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('');

        $appResponse = call_user_func($this->app, $request);
        $this->assertTrue($appResponse->hasErrors());
    }
    
    function test_invoke_shouldReturnApplicationResponseWithEmptyArticlePOCO_whenArticleFinderNotFindArticle(){
        $request = $this->createMock(ReadArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('xxx');
        
        $this->articleFinder->expects($this->once())->method('findArticles')->willReturnSelf();
        $this->articleFinder->expects($this->once())->method('getFirstOrThrow')->willThrowException(new ArticleFinderException());

        $appResponse = call_user_func($this->app, $request);
        $this->assertEquals(ParkingPOCO::empty(),$appResponse->getArticle());
    }
    function test_invoke_shouldReturnApplicationResponseWithError_whenArticleFinderNotFindArticle(){
        $request = $this->createMock(ReadArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('xxx');
        $this->articleFinder->expects($this->once())->method('findArticles')->willReturnSelf();
        $this->articleFinder->expects($this->once())->method('getFirstOrThrow')->willThrowException(new ArticleFinderException());
        
        $appResponse = call_user_func($this->app, $request);
        $this->assertTrue($appResponse->hasErrors());
    }
    
    function test_invoke_shouldReturnApplicationResponseWithArticlePOCO_whenArticleFound(){
        $article=TestHelper::get()->makeArticle();
        $request = $this->createMock(ReadArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('xxx');
        $this->articleFinder->expects($this->once())->method('findArticles')->willReturnSelf();
        $this->articleFinder->expects($this->once())->method('getFirstOrThrow')->willReturn($article);
        
        $appResponse = call_user_func($this->app, $request);
        $this->assertFalse($appResponse->hasErrors());
        $this->assertEquals(ParkingPOCO::of($article), $appResponse->getArticle());
    }
    
    

}
