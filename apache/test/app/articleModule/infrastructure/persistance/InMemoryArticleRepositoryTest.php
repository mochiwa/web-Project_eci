<?php

namespace Test\App\Article\Infrastructure\Persistance;

/**
 * Dont launch this test , run the test in the model !
 *
 * @author mochiwa
 */
class InMemoryArticleRepositoryTest extends \Test\App\Article\Model\Article\ArticleRepositoryTest{
    public function setUp() {
        $this->repository=new \App\Article\Infrastructure\Persistance\InMemory\InMemoryArticleRepository();
    }
}
