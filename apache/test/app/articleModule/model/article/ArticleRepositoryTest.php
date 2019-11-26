<?php
namespace Test\App\Article\Model\Article;

use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\EntityNotFoundException;
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
        $this->assertEquals($article, $this->repository->findById($article->id()));
        
    }
    
    function test_findById_shouldThrowEntityNotFoundException_whenRepositoryNotContainsTheArticle()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->repository->findById(ArticleId::of('aaa'));
        
    }
}
