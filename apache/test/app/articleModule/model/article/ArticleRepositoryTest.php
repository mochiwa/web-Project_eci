<?php
namespace Test\App\Article\Model\Article;

use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Title;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Do not execute this test ! run its implementation
 *
 * @author mochiwa
 */
abstract class ArticleRepositoryTest extends TestCase{
    /**
     *
     * @var IArticleRepository
     */
    protected $repository;
    
    function test_nextId_shouldGenerateNewId_whenItCalled(){
        $id_a=$this->repository->nextId();
        $id_b=$this->repository->nextId();
        
        $this->assertNotSame($id_a, $id_b);
    }
    
    function test_findById_shouldReturnTheArticle_whenRepositoryContainsTheArticle()
    {
        $article= TestHelper::get()->makeArticle();
        $this->repository->addArticle($article);
        $this->assertEquals($article->id(), $this->repository->findById($article->id())->id());
        
    }
    
    function test_findById_shouldThrowEntityNotFoundException_whenRepositoryNotContainsTheArticle()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->repository->findById(ArticleId::of('aaa'));
    }
    
    function test_isArticleTitleExist_shouldReturnFalse_whenArticleTitleIsNotUsed()
    {
        $this->assertFalse($this->repository->isArticleTitleExist(Title::of('ATitle')));
    }
    
    function test_isArticleTitleExist_shouldReturnTrue_whenArticleTitleIsAlreadyUsed()
    {
        $this->repository->addArticle(TestHelper::get()->makeArticle('aaa','ATitle'));
        $this->assertTrue($this->repository->isArticleTitleExist(Title::of('ATitle')));
    }
    
    function test_isArticleIdExist_shouldReturnFalse_whenArticleIdisFree()
    {
        $this->assertFalse($this->repository->isArticleIdExist(ArticleId::of('aaa')));
    }
    
    function test_isArticleTitleExist_shouldReturnTrue_whenArticleTitleIdAlreadyUsed()
    {
        $this->repository->addArticle(TestHelper::get()->makeArticle('aaa'));
        $this->assertTrue($this->repository->isArticleIdExist(ArticleId::of('aaa')));
    }
    
    function test_removeArticle_shouldThrowEntityNotFoundException_whenRepositoryNotContainsTheArticle()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->repository->removeArticle(ArticleId::of("anId"));
    }

    
    function test_removeArticle_shouldRemoveTheArticleFromTheRepository_whenItHasBeenfound()
    {
        $article= TestHelper::get()->makeArticle('aaa');
        $this->repository->addArticle($article);
        $this->repository->removeArticle(ArticleId::of("aaa"));
        
        $this->expectException(EntityNotFoundException::class);
        $this->repository->findById(ArticleId::of('aaa'));
    }
    
    function test_findByTitle_shouldThrowEntityNotFoundException_whenAnyArticleWithTitleExist()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->repository->findByTitle(Title::of("NotExisting"));
    }
    
    function test_findByTitle_shouldReturnTheArticle_whenArticleWithTitleExist()
    {
        $article= TestHelper::get()->makeArticle('aaa','aTitle');
        $this->repository->addArticle($article);
        $this->assertNotNull($this->repository->findByTitle(Title::of('aTitle')));
    }
    
    function test_update_shouldThrowEntityNotFoundExpection_whenArticleNotFoundInRepository()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->repository->update(TestHelper::get()->makeArticle());
    }
    
    function test_update_shouldUpdateAllInformationEditable_whenArticleFoundInRepository()
    {
        $original=TestHelper::get()->makeArticle('aaa');
        $edited=TestHelper::get()->makeArticle('aaa','editedTitle');
        $this->repository->addArticle($original);
        
        $this->repository->update($edited);
        $fromDatabase=$this->repository->findById($original->id());
        $this->assertEquals($edited->id(), $fromDatabase->id());
        $this->assertNotEquals($original, $fromDatabase);
    }
    
    
    function test_sizeof_shouldReturn0_whenThereAreNoArticle()
    {
        $this->assertEquals(0, $this->repository->sizeof());
    }
    function test_sizeof_shouldReturn1_whenThereAreOneArticle()
    {
        $this->repository->addArticle(TestHelper::get()->makeArticle('aaa'));
        $this->assertEquals(1, $this->repository->sizeof());
    }
    function test_getASetOfArticles_shouldReturnArrayWith3Article_whenMaxIs3AndCurrentIs0()
    {
        $this->repository->addArticle(TestHelper::get()->makeArticle('aaa'));
        $this->repository->addArticle(TestHelper::get()->makeArticle('bbb'));
        $this->repository->addArticle(TestHelper::get()->makeArticle('ccc'));
        $this->assertEquals(3, sizeof($this->repository->setOfArticles(0,3)));
    }
    function test_getASetOfArticles_shouldReturnEmptyArray_whenCurrentIsSupperiorThanSizeOfData()
    {
        $this->repository->addArticle(TestHelper::get()->makeArticle('aaa'));
        $this->assertEquals(0, sizeof($this->repository->setOfArticles(3,3)));
    }
    function test_getASetOfArticles_shouldReturnEmptyArray_whenTheyAreNoData()
    {
        $this->assertEquals(0, sizeof($this->repository->setOfArticles(0,3)));
    }
    
    function test_getASetOfArticles_shouldReturn2Article_whenTheyAre5DataAndCurrentEquals3AndMaxEquals5()
    {
        $this->repository->addArticle(TestHelper::get()->makeArticle('aaa'));
        $this->repository->addArticle(TestHelper::get()->makeArticle('bbb'));
        $this->repository->addArticle(TestHelper::get()->makeArticle('ccc'));
        $this->repository->addArticle(TestHelper::get()->makeArticle('ddd'));
        $this->repository->addArticle(TestHelper::get()->makeArticle('eee'));
        $this->assertEquals(2, sizeof($this->repository->setOfArticles(3,5)));
    }
    
}
