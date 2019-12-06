<?php

namespace Test\Article\Model\Article\Service\Finder;

use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\Finder\FindById;
use PHPUnit\Framework\TestCase;

/**
 * Description of FindByIdTest
 *
 * @author mochiwa
 */
class FindByIdTest extends TestCase {

    private $repository;
    private $finder;

    protected function setUp() {
        $this->repository = $this->createMock(IArticleRepository::class);
        $this->finder= FindById::fromStringID('notExisting');
    }

    function test_invoke_shouldReturnAnEmptyArray_whenNoArticleWithThisIdFound()
    {
        
        $this->repository->expects($this->once())->method('findById')->willThrowException(new EntityNotFoundException(''));
        $this->assertEmpty(call_user_func($this->finder,$this->repository));
    }
    function test_invoke_shouldReturnAnArrayWithTheArticleSeek_whenArticleWithThisIdFound()
    {
        $article=\Test\App\TestHelper::get()->makeArticle('1');
        $this->repository->expects($this->once())->method('findById')->willReturn($article);
        $this->assertSame($article, call_user_func($this->finder,$this->repository)[0]);
    }
}
