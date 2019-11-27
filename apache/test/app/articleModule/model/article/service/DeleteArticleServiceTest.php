<?php
namespace Test\Article\Model\Article\Service;

use App\Article\Infrastructure\Persistance\InMemory\InMemoryArticleRepository;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\EntityNotFoundException;
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
        $this->repository=new InMemoryArticleRepository();
        $this->service=new DeleteArticleService($this->repository);
        
    }
    
    function test_execute_shouldEntityNotFoundException_whenArticleIsNotInRepository()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->service->execute(new DeleteArticleRequest("aNotExistingId"));
    }
    
    function test_execute_shouldRemoveEntityFromRepository_whenArticleIsInRepository()
    {
        $this->repository->addArticle(TestHelper::get()->makeArticle('aaa'));
        $this->service->execute(new DeleteArticleRequest("aaa"));
        $this->assertFalse($this->repository->isArticleIdExist(ArticleId::of('aaa')));
    }
}
