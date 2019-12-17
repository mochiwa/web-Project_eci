<?php

use App\Article\Application\Service\Dto\ParkingView;
use App\Article\Application\Service\Request\ShowArticleRequest;
use App\Article\Application\Service\ShowArticleApplication;
use App\Article\Model\Article\Service\ArticleFinder;
use App\Article\Model\Article\Service\Response\ArticleDomainResponse;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;

/**
 * Description of ShowArticleApplicationTest
 *
 * @author mochiwa
 */
class ShowArticleApplicationTest extends TestCase{
    private $articleFinder;
    private $app;
    
    protected function setUp() {
        $this->articleFinder=$this->createMock(ArticleFinder::class);
        $this->app=new ShowArticleApplication($this->articleFinder);
    }
    
    
    function test_invoke_shouldReturnResponseWithError_whenFinderNotFindArticle()
    {
        $this->articleFinder->expects($this->once())->method('findArticles')->willReturnSelf();
        $this->articleFinder->expects($this->once())->method('getFirst')->willReturn(null);

        
        $res= call_user_func($this->app,new ShowArticleRequest('anId'));
        $this->assertTrue($res->hasErrors());
        $this->assertEquals(ParkingView::empty(), $res->getArticle());
    }
    
  /*  function test_invoke_shouldReturnResponse_whenFinderIsFound()
    {
        $article= new ArticleDomainResponse(TestHelper::get()->makeArticle());
        $this->articleFinder->expects($this->once())->method('findArticles')->willReturnSelf();
        $this->articleFinder->expects($this->once())->method('getFirst')->willReturn($article);

        
        $res= call_user_func($this->app,new ShowArticleRequest('anId'));
        $this->assertFalse($res->hasErrors());
        $this->assertEquals($article, $res->getArticle());
    }*/
}
