<?php
namespace Test\Article\Model\Article\Service;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use App\Article\Model\Article\Title;
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
        $this->request=EditArticleRequest::of(ArticleId::of('aaa'),Title::of('ArticleTitle'), Picture::of('',''),[Attribute::of('key', 'value')],"Description");
        
    }
    
    function test_invoke_shouldThrowEntityNotFoundException_whenArticleNotFoundInRepository()
    {
        $this->repository->method('isArticleIdExist')->willReturn(false);
        
        $this->expectException(EntityNotFoundException::class);
        call_user_func($this->service,$this->request);
    }
    
    function test_invoke_shouldThrowArticleException_whenArticleTitleIsAlreadyUsedByAnotherArticle()
    {
        $original= TestHelper::get()->makeArticle('aaa','ArticleTitle');
        $this->repository->method('isArticleIdExist')->willReturn(true);
        $this->repository->method('findById')->willReturn($original);
        $this->repository->method('isArticleTitleExist')->willReturn(true);
        $this->repository->method('findByTitle')->willReturn(TestHelper::get()->makeArticle('bbb','ArticleTitle'));
        
        $this->expectException(ArticleException::class);
        call_user_func($this->service,$this->request);
    }
    
    function test_invoke_shouldCommitChange_whenArticleTitleItIsSameAsOriginal()
    {
        $original= TestHelper::get()->makeArticle('aaa','ArticleTitle');
        $this->repository->method('isArticleIdExist')->willReturn(true);
        $this->repository->method('findById')->willReturn($original);
        $this->repository->method('isArticleTitleExist')->willReturn(true);
        $this->repository->method('findByTitle')->willReturn($original);
        
        $this->repository->expects($this->once())->method('update');
        call_user_func($this->service,$this->request);
    }
    
     function test_invoke_shouldCommitChange_whenArticleTitleIsNotUsed()
    {
        $original= TestHelper::get()->makeArticle('aaa','ArticleTitle');
        $this->repository->method('isArticleIdExist')->willReturn(true);
        $this->repository->method('findById')->willReturn($original);
        $this->repository->method('isArticleTitleExist')->willReturn(false);
        
        $this->repository->expects($this->once())->method('update');
        call_user_func($this->service,$this->request);
    }
    
    function test_invoke_shouldNotUpdateThePicture_whenItIsEmpty()
    {
        $original= TestHelper::get()->makeArticle('aaa','ArticleTitle','my/picture');
        $this->repository->method('isArticleIdExist')->willReturn(true);
        $this->repository->method('findById')->willReturn($original);
        $this->repository->method('isArticleTitleExist')->willReturn(false);
        
        $this->repository->expects($this->once())->method('update')->
                with($this->callback(function($article) use($original) {return $article->picture()==$original->picture();}));
        call_user_func($this->service,$this->request);
    }
    
    function test_invoke_shouldUpdateThePicture_whenItIsNotEmpty()
    {
        $original= TestHelper::get()->makeArticle('aaa','ArticleTitle','my/picture');
        $this->repository->method('isArticleIdExist')->willReturn(true);
        $this->repository->method('findById')->willReturn($original);
        $this->repository->method('isArticleTitleExist')->willReturn(false);
        
        $this->repository->expects($this->once())->method('update')->
                with($this->callback(function($article) use($original) {return $article->picture()==$original->picture();}));
        call_user_func($this->service,$this->request);
    }
}
