<?php

use Framework\Paginator\IPaginable;
use Framework\Paginator\Paginator;
use Framework\Paginator\PaginatorException;
use PHPUnit\Framework\TestCase;

/**
 * Description of PaginatorTest
 *
 * @author mochiwa
 */
class PaginatorTest extends TestCase{
    private $paginable;
    private $paginator;
    
    protected function setUp() {
        $this->paginable=$this->createMock(IPaginable::class);
        $this->paginator=new Paginator($this->paginable,5);
    }
    
    function test_setMaxDataPerPage_shouldThrowPaginatorException_whenCountIsEquals_0()
    {
        $this->expectException(PaginatorException::class);
        $this->paginator->setMaxDataPerPage(0);
    }
    function test_setMaxDataPerPage_shouldThrowPaginatorException_whenCountLessThan_0()
    {
        $this->expectException(PaginatorException::class);
        $this->paginator->setMaxDataPerPage(-10);
    }
    
    
    function test_pageCount_shouldReturn_1_whenDataCountIsLessThanMaxDataPerPage()
    {
        $this->paginator->setMaxDataPerPage(5);
        $this->paginable->expects($this->once())->method('dataCount')->willReturn(4); 
        $this->assertEquals(1, $this->paginator->pageCount());
    }
    function test_pageCount_shouldReturn_1_whenDataCountIsEquals_0()
    {
        $this->paginator->setMaxDataPerPage(5);
        $this->paginable->expects($this->once())->method('dataCount')->willReturn(0); 
        $this->assertEquals(1, $this->paginator->pageCount());
    }
    function test_pageCount_shouldReturn_2_whenDataCountIsEquals_10_andMaxDataPerPageEquals_5()
    {
        $this->paginator->setMaxDataPerPage(5);
        $this->paginable->expects($this->once())->method('dataCount')->willReturn(10); 
        $this->assertEquals(2, $this->paginator->pageCount());
    }
    function test_pageCount_shouldReturn_2_whenDataCountIsEquals_10_andMaxDataPerPageEquals_7()
    {
        $this->paginator->setMaxDataPerPage(7);
        $this->paginable->expects($this->once())->method('dataCount')->willReturn(10); 
        $this->assertEquals(2, $this->paginator->pageCount());
    }
    
    function test_constructor_shouldInitializeMaxDataPerPageTo_5_whenItIsNotSpecified()
    {
        $this->assertEquals(5, $this->paginator->maxDataPerPage());
    }
    
    
    function test_getDataForPage_shouldReturnAnEmptyArray_whenNoDataFoundFromPaginableForThisPage()
    {
        $this->paginable->expects($this->once())->method('getForPagination')->willReturn([]);
        $this->assertSame([], $this->paginator->getDataForPage(1));
    }
    
    function test_getDataForPage_shouldReturn_1_2_3_InArray_whenTheseDataExistAnPageCanContain3Data()
    {
        $this->paginable->expects($this->once())->method('getForPagination')->willReturn([1,2,3]);
        $this->paginator->setMaxDataPerPage(3);
        $this->assertSame([1,2,3], $this->paginator->getDataForPage(1));
    }
    
    function test_getDataForPage_shouldReturn_1_2_3_InArray_whenTheseDataExistAnPageCanContain3DataAndPaginableHasMoreData()
    {
        $this->paginable->expects($this->once())->method('getForPagination')->will($this->returnCallback(function($current,$max){
            $data=[1,2,3,4,5];
            return array_slice($data,$current, $max);
        }));
        $this->paginator->setMaxDataPerPage(3);
        $this->assertSame([1,2,3], $this->paginator->getDataForPage(1));
    }
    function test_getDataForPage_shouldReturn_4_5_InArray_whenTheseDataExistAnPageCanContain3DataAndPaginableHasDatas()
    {
        $this->paginable->expects($this->once())->method('getForPagination')->will($this->returnCallback(function($current,$max){
            $data=[1,2,3,4,5];
            return array_slice($data,$current, $max);
        }));
        $this->paginable->expects($this->once())->method('dataCount')->willReturn(5);
        $this->paginator->setMaxDataPerPage(3);
        $this->assertSame([4,5], $this->paginator->getDataForPage(2));
    }
    
    function test_getDataForPage_shouldReturnEmptyArray_whenPageIsSuperiorThanThePageCount()
    {
        $this->paginator->setMaxDataPerPage(3);
        $this->paginable->expects($this->never())->method('getForPagination');
        $this->assertSame([], $this->paginator->getDataForPage(20));
    }
    function test_getDataForPage_shouldReturnEmptyArray_whenPageIsLessThan0()
    {
        $this->paginable->expects($this->never())->method('getForPagination');
        $this->assertSame([], $this->paginator->getDataForPage(-1));
    }
     function test_getDataForPage_shouldReturnEmptyArray_whenPageIsEquals0()
    {
        $this->paginable->expects($this->never())->method('getForPagination');
        $this->assertSame([], $this->paginator->getDataForPage(0));
    }
    
    function test_getDataForPage_shouldReturnLastArticle_whenNoMoreArticleCanBeDisplayed()
    {
        $this->paginable->expects($this->once())->method('getForPagination')->will($this->returnCallback(function($current,$max){
            $data=[1,2,3,4,5];
            return array_slice($data,$current, $max);
        }));
        $this->paginable->expects($this->once())->method('dataCount')->willReturn(5);
        $this->paginator->setMaxDataPerPage(2);
        
        $this->assertSame([5], $this->paginator->getDataForPage(3));
    }
}
