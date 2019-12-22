<?php

use App\Article\Infrastructure\Service\ArticlePaginatorService;
use App\Article\Model\Article\IArticleRepository;
use PHPUnit\Framework\TestCase;

/**
 * Description of PaginationServiceTest
 *
 * @author mochiwa
 */
class ParkingPaginatorServiceTest extends TestCase{
    private $repository;
    private $service;
    
    protected function setUp() {
        $this->repository=$this->createMock(IArticleRepository::class);
        $this->service=new ArticlePaginatorService($this->repository);
    }
    
    public function test_getDataForPage_shouldReturnEmptyArray_whenRepositoryReturnAnyData(){
        $this->repository->expects($this->once())->method('setOfArticles')->willReturn([]);
        
        $this->assertEquals([], $this->service->getDataForPage(1,10));
    }
    public function test_getDataForPage_shouldReturnEmptyArray_whenCurrentPageIsLessOrEqualsThan0(){
        $this->repository->expects($this->never())->method('setOfArticles');
        
        $this->assertEquals([], $this->service->getDataForPage(0,10));
        $this->assertEquals([], $this->service->getDataForPage(-5,10));
    }
    public function test_getDataForPage_shouldReturnEmptyArray_whenCurrentPageIsSupperiorThanTotalPageCount(){
        $this->repository->expects($this->never())->method('setOfArticles');
        $this->repository->expects($this->once())->method('sizeof')->willReturn(1);
        
        $this->assertEquals([], $this->service->getDataForPage(2,1));
    }
    public function test_getDataForPage_shouldReturnEmptyArray_whenArticlePerPageEquals0(){
        $this->repository->expects($this->never())->method('setOfArticles');
        $this->assertEquals([], $this->service->getDataForPage(2,0));
    }
    public function test_getDataForPage_shouldReturnEmptyArray_whenArticlePerPageIsLessThan0(){
        $this->repository->expects($this->never())->method('setOfArticles');
        
        $this->assertEquals([], $this->service->getDataForPage(2,-1));
    }
    function test_getDataForPage_shouldReturnArrayWith_1_2_3_whenRepositoryReturnThese_And_PageCanHave3Data()
    {
        $this->repository->expects($this->once())->method('setOfArticles')->willReturn([1,2,3]);
        $this->assertSame([1,2,3], $this->service->getDataForPage(1,3));
    }
    
    
    function test_pageCount_shouldReturn_1_whenDataCountIsEquals_0(){
        $this->repository->expects($this->once())->method('sizeof')->willReturn(0); 
        $this->assertEquals(1, $this->service->pageCount(2));
    }
    function test_pageCount_shouldReturn_1_whenRepositoryHas2Datas_And_MaxDataPerPAgeEquals3(){
        $this->repository->expects($this->once())->method('sizeof')->willReturn(2); 
        $this->assertEquals(1, $this->service->pageCount(3));
    }
    function test_pageCount_shouldReturn_1_whenRepositoryHas2Datas_And_MaxDataPerPAgeEquals2(){
        $this->repository->expects($this->once())->method('sizeof')->willReturn(2); 
        $this->assertEquals(1, $this->service->pageCount(2));
    }
    
    
}