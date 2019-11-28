<?php

namespace Test\Article\Model\Article\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;

/**
 * Description of EditArticleService
 *
 * @author mochiwa
 */
class EditArticleServiceTest extends TestCase{
    
    private $repository;
    private $service;
    private $request;
    public function setUp()
    {
        $this->repository=$this->createMock(IArticleRepository::class);
        $this->service=new EditArticleService($this->repository);
        $this->request=new EditArticleRequest('aaa','ArticleTitle','picture',['att'=>"value"],"Description");
        
    }
    
    function test_execute_shouldThrowEntityNotFoundException_whenArticleNotFoundInRepository()
    {
        $this->repository->method('isArticleIdExist')->willReturn(false);
        
        $this->expectException(EntityNotFoundException::class);
        $this->service->exectue($this->request);
    }
    
    function test_execute_shouldThrowArticleException_whenArticleTitleIsAlreadyUsedByAnotherArticle()
    {
        $original= TestHelper::get()->makeArticle('aaa','ArticleTitle');
        $this->repository->method('isArticleIdExist')->willReturn(true);
        $this->repository->method('findById')->willReturn($original);
        $this->repository->method('isArticleTitleExist')->willReturn(true);
        $this->repository->method('findByTitle')->willReturn(TestHelper::get()->makeArticle('bbb','ArticleTitle'));
        
        $this->expectException(ArticleException::class);
        $this->service->exectue($this->request);
    }
    
    function test_execute_shouldCommitChange_whenArticleTitleItIsSameAsOriginal()
    {
        $original= TestHelper::get()->makeArticle('aaa','ArticleTitle');
        $this->repository->method('isArticleIdExist')->willReturn(true);
        $this->repository->method('findById')->willReturn($original);
        $this->repository->method('isArticleTitleExist')->willReturn(true);
        $this->repository->method('findByTitle')->willReturn($original);
        
        $this->repository->expects($this->once())->method('update');
        $this->service->exectue($this->request);
    }
    
     function test_execute_shouldCommitChange_whenArticleTitleIsNotUsed()
    {
        $original= TestHelper::get()->makeArticle('aaa','ArticleTitle');
        $this->repository->method('isArticleIdExist')->willReturn(true);
        $this->repository->method('findById')->willReturn($original);
        $this->repository->method('isArticleTitleExist')->willReturn(false);
        
        $this->repository->expects($this->once())->method('update');
        $this->service->exectue($this->request);
    }
}
