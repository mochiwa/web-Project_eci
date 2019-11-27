<?php
namespace Test\Article\Model\Article\Service;

use App\Article\Infrastructure\Persistance\InMemory\InMemoryArticleRepository;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
use App\Article\Model\Article\Service\CreateArticleService;
use PHPUnit\Framework\TestCase;
/**
 * Description of CreateArticleServiceTest
 *
 * @author mochiwa
 */
class CreateArticleServiceTest extends TestCase{
    private $articleRepository;
    private $service;
    public function setUp()
    {
        $this->articleRepository=$this->createMock(InMemoryArticleRepository::class);
        $this->service=new CreateArticleService($this->articleRepository);
    }
    
    function test_execute_shouldReturnANewArticle_whenNoErrorAreDetected()
    {
        $request=new CreateArticleRequest('ArticleTitle','picture',['att'=>"value"],"Description");
        $article=$this->service->execute($request);
        $this->assertNotNull($article);
    }
    
    function test_execute_shouldAppendTheArticleInRepository_whenNoErrorOccure()
    {
        $request=new CreateArticleRequest('ArticleTitle','picture',['att'=>"value"],"Description");
        $article=$this->service->execute($request);
        $this->assertNotNull($this->articleRepository->findById($article->id()));
    }
    
    function test_execute_shouldThrowArticleException_whenArticleTitleAlreadyUsed()
    {
        $this->articleRepository->method('isArticleTitleExist')->willReturn(true);
        
        $request=new CreateArticleRequest('ArticleTitle','picture',['att'=>"value"],"Description");
        
        $this->expectException(ArticleException::class);
        $article=$this->service->execute($request);
        
    }
    
}
