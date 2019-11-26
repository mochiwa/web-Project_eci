<?php
namespace Test\App\Article\Model\Article;
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
abstract class ArticleRepositoryTest extends \PHPUnit\Framework\TestCase{
    protected $repository;
    
    function test_nextId_shouldGenerateNewId_whenItCalled(){
        $id_a=$this->repository->nextId();
        $id_b=$this->repository->nextId();
        
        $this->assertNotSame($id_a, $id_b);
    }
}
