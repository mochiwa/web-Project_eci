<?php

namespace Test\App\Article\Infrastructure\Persistance;

use App\Article\Infrastructure\Persistance\InMemory\InMemoryArticleRepository;
use Test\App\Article\Model\Article\ArticleRepositoryTest;

/**
 * Don't launch this test , run the test in the model !
 *
 * @author mochiwa
 */
class InMemoryArticleRepositoryTest extends ArticleRepositoryTest{
    public function setUp() {
        $this->repository=new InMemoryArticleRepository();
        
    }
    
    function tearDown() {
        $this->repository->clear();
    }
}
