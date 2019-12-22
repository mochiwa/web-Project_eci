<?php
namespace Test\Article\Model\Article\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
use App\Article\Model\Article\Title;
use PHPUnit\Framework\TestCase;
/**
 * Description of CreateArticleServiceTest
 *
 * @author mochiwa
 */
class CreateArticleServiceTest extends TestCase{
    private $repository;
    private $service;
    private $request;
    public function setUp()
    {
        $this->repository=$this->createMock(IArticleRepository::class);
        $this->service=new CreateArticleService($this->repository);
        $this->request=$this->createMock(CreateArticleRequest::class);
    }

    function test_execute_shouldAppendTheArticleInRepository_whenNoErrorOccure()
    {
        $this->repository->expects($this->once())->method('addArticle');
        $article=call_user_func($this->service,$this->request);
        $this->assertNotNull($article);
    }
    
    
    
    function test_execute_shouldThrowArticleException_whenArticleTitleAlreadyUsed()
    {
        $this->repository->expects($this->once())->method('isArticleTitleExist')->willReturn(true);
        
        $this->expectException(ArticleException::class);
        call_user_func($this->service,$this->request);
    }
    
    function test_execute_shouldNameThePictureLike_article_titleArticleId()
    {
        $this->repository->expects($this->once())->method('nextId')->willReturn(ArticleId::of('01'));
        $this->repository->expects($this->once())->method('isArticleTitleExist')->willReturn(false);

        $article= call_user_func($this->service,$this->request);
        $this->assertequals('article-01',$article->picture()->path());
    }
    
}
