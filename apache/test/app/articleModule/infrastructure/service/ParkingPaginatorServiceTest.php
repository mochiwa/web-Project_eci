<?php

use App\Article\Infrastructure\Service\ParkingPaginatorService;
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
        $this->service=new ParkingPaginatorService($this->repository);
    }
    
    public function test_getDataForPage_shouldReturnEmptyArray_whenRepositoryReturnAnyData(){
        $this->repository->expects($this->once())->method('getASetOfArticles')->with(1,10)->willReturn([]);
        
        $this->assertEquals([], $this->service->getDataForPage(1,10));
    }
    public function test_getDataForPage_shouldReturnEmptyArray_whenCurrentPageIsLessOrEqualsThan0(){
        $this->repository->expects($this->never())->method('getASetOfArticles');
        
        $this->assertEquals([], $this->service->getDataForPage(0,10));
        $this->assertEquals([], $this->service->getDataForPage(-5,10));
    }
    public function test_getDataForPage_shouldReturnEmptyArray_whenCurrentPageIsSupperiorThanTotalPageCount(){
        $this->repository->expects($this->never())->method('getASetOfArticles');
        $this->repository->expects($this->once())->method('sizeof')->willReturn(1);
        
        $this->assertEquals([], $this->service->getDataForPage(2,1));
    }
    public function test_getDataForPage_shouldThrowException_whenArticlePerPageEquals0(){
        $this->repository->expects($this->never())->method('getASetOfArticles');
        
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals([], $this->service->getDataForPage(2,0));
    }
    public function test_getDataForPage_shouldThrowException_whenArticlePerPageIsLessThan0(){
        $this->repository->expects($this->never())->method('getASetOfArticles');
        
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals([], $this->service->getDataForPage(2,-1));
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
