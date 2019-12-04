<?php

namespace Test\Article\Model\Article\Service;

use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\GettingArticleService;
use App\Article\Model\Article\Service\Request\GettingSingleArticleByIdRequest;
use App\Article\Model\Article\Service\Response\ArticleViewResponse;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;

/**
 * Description of GettingArticleServiceTest
 *
 * @author mochiwa
 */
class GettingArticleServiceTest extends TestCase{
    private $service;
    private $repository;
    private $request;
    
    function setUp() {
        $this->repository=$this->getMockBuilder(IArticleRepository::class)->getMock();
        
        $this->service=new GettingArticleService($this->repository);
        $this->request=new GettingSingleArticleByIdRequest('articleId');
    }
    
    function test_execute_shouldThrowEntityNotFoundException_whenArticleNotFoundInRepository()
    {
        $this->repository->expects($this->once())->method('isArticleIdExist')->willReturn(false);
        $this->expectException(EntityNotFoundException::class);
        $this->service->execute($this->request);
    }
    
    
    function test_execute_shouldReturnAnArticleDomainResponse_WhenItFoundInRepository()
    {
        $article= TestHelper::get()->makeArticle('aaa');
        $articleView=new \App\Article\Model\Article\Service\Response\ArticleDomainResponse($article);
        
        $this->repository->expects($this->once())->method('isArticleIdExist')->willReturn(true);
        $this->repository->expects($this->once())->method('findById')->willReturn($article);
        $articleFound=$this->service->execute(new GettingSingleArticleByIdRequest('aaa'));
        $this->assertEquals($articleFound,$articleView);
    }
    
    
    
}
