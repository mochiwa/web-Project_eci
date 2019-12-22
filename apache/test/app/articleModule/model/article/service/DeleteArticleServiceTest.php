<?php
namespace Test\Article\Model\Article\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\DeleteArticleService;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;

/**
 * This Domain service is responsible to 
 * delete an article from repository
 * @author mochiwa
 */
class DeleteArticleServiceTest extends TestCase{
    private $repository;
    private $service;
    
    public function setUp()
    {
        $this->repository=$this->createMock(IArticleRepository::class);
        $this->service=new DeleteArticleService($this->repository);
    }
    
    function test_invoke_shouldThrowArticleException_whenRepositoryNotFoundTheArticle(){
        $request=$this->createMock(DeleteArticleRequest::class);
        $this->repository->expects($this->once())->method('findById')->willThrowException(new EntityNotFoundException());
        $this->expectException(ArticleException::class);
        call_user_func($this->service,$request);
    }
    
    function test_invoke_shouldRemoveArticleFromRepository_whenRepositoryHasTheArticle(){
        $request=$this->createMock(DeleteArticleRequest::class);
        $this->repository->expects($this->once())->method('findById')->willReturn(TestHelper::get()->makeArticle());
        $this->repository->expects($this->once())->method('removeArticle');
        call_user_func($this->service,$request);
    }
    
    function test_invoke_shouldReturnTheArticleDeleted_whenArticleSuccessfulyRemovedFromRepository(){
        $article= TestHelper::get()->makeArticle();
        $request=$this->createMock(DeleteArticleRequest::class);
        $this->repository->expects($this->once())->method('findById')->willReturn($article);
        $this->repository->expects($this->once())->method('removeArticle');
        $domainResponse=call_user_func($this->service,$request);
        $this->assertSame($article, $domainResponse);
    }
}
