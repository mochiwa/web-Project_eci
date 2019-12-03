<?php
namespace Test\Article\Model\Article\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
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
        $this->request=new CreateArticleRequest('ArticleTitle','picture',['att'=>"value"],"Description");
    }

    function test_execute_shouldAppendTheArticleInRepository_whenNoErrorOccure()
    {
        $this->repository->expects($this->once())->method('addArticle');
        $this->service->execute($this->request);
    }
    
    function test_execute_shouldThrowArticleException_whenArticleTitleAlreadyUsed()
    {
        $this->repository->expects($this->once())->method('isArticleTitleExist')->willReturn(true);
        
        $this->expectException(ArticleException::class);
        $this->service->execute($this->request);
    }
    
    function test_execute_shouldNameThePictureLike_artile_titleArticleId()
    {
        $this->repository->expects($this->once())->method('nextId')->willReturn(ArticleId::of('01'));
        $this->repository->expects($this->once())->method('isArticleTitleExist')->willReturn(false);

        $article=$this->service->execute($this->request);
        $this->assertequals('article-01',$article->picture()->path());
    }
    
}
