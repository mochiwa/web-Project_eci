<?php

namespace Test\Article\Model\Article\Service;

use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\ArticleFinder;
use App\Article\Model\Article\Service\Finder\IFinder;
use App\Article\Model\Article\Service\Response\ArticleDomainResponse;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;

/**
 * Description of ArticleFinderServiceTest
 *
 * @author mochiwa
 */
class ArticleFinderServiceTest extends TestCase{
    private $finder;
    private $service;
    private $repository;
    private $articles;
    
    
    
    protected function setUp() {
        $this->repository=$this->createMock(IArticleRepository::class);
        $this->finder=$this->createMock(IFinder::class);
        $this->service=new ArticleFinder($this->repository);
        $this->articles=[
            TestHelper::get()->makeArticle('1'),
            TestHelper::get()->makeArticle('2'),
            TestHelper::get()->makeArticle('3')
        ];
    }
    
    public function test_getFirst_shouldReturnNull_whenFinderHasNoData()
    {
        $this->finder->expects($this->any())->method('__invoke')->willReturn([]);
        
        $this->service->findArticles($this->finder);
        $this->assertNull($this->service->getFirst());
    }
    
    public function test_getFirst_shouldReturnTheFirstObjectFound_whenFinderHasFoundDataFromFinder()
    {
        
        $this->finder->expects($this->any())->method('__invoke')->willReturn($this->articles);
        
        $this->service->findArticles($this->finder);
        
        $this->assertEquals($this->articles[0]->id(),$this->service->getFirst()->id());
    }
            
    public function test_getLast_shouldReturnNull_whenFinderHasNoData()
    {
        $this->finder->expects($this->any())->method('__invoke')->willReturn([]);
        
        $this->service->findArticles($this->finder);
        
        $this->assertNull($this->service->getLast());
    }       
    
    public function test_getLast_shouldReturnTheLastObjectFound_whenFinderHasFoundDataFromFinder()
    {
        $this->finder->expects($this->any())->method('__invoke')->willReturn($this->articles);
        
        $this->service->findArticles($this->finder);
        
        $this->assertEquals(end($this->articles)->id(),$this->service->getLast()->id());
    }
    
    public function test_getArticles_shouldReturnEmptyArray_whenFinderHasNotFoundDataFromFinder()
    {
        $this->finder->expects($this->any())->method('__invoke')->willReturn([]);
        
        $this->service->findArticles($this->finder);
        
        $this->assertEmpty($this->service->getArticles());
    }
    
    public function test_getArticles_shouldReturnAllDataIntoArray_whenFinderHasFoundDataFromFinder()
    {
        $this->finder->expects($this->any())->method('__invoke')->willReturn($this->articles);
        
        $this->service->findArticles($this->finder);
        
        $this->assertSame($this->articles[0]->id(),$this->service->getArticles()[0]->id());
    }
    public function test_getArticles_shouldReturnInstancesOfArticleDomainResponse_whenFinderHasFoundDataFromFinder()
    {
        $this->finder->expects($this->any())->method('__invoke')->willReturn($this->articles);
        
        $this->service->findArticles($this->finder);
        
        $this->assertInstanceOf(ArticleDomainResponse::class, $this->service->getArticles()[0]);
    }
    
    
    
}
