<?php

use Framework\Paginator\Pagination;
use PHPUnit\Framework\TestCase;

/**
 * Description of PaginationTest
 *
 * @author mochiwa
 */
class PaginationTest extends TestCase {
    
    function test_pageCount_shouldBeEquals_1_WhenItisNotspecified()
    {
        $pagination=new Pagination();
        $this->assertEquals(1, $pagination->getPageCount());
    }
    function test_setTotalPageCount_shouldSetPageCountTo_1_whenArgumentIsLessOrEqualsThan0()
    {
        $pagination=new Pagination();
        $pagination->setTotalPageCount(0);
        $this->assertEquals(1, $pagination->getPageCount());
        
        $pagination->setTotalPageCount(-1);
        $this->assertEquals(1, $pagination->getPageCount());
    }
    
    function test_previous_shouldReturn_1_whenCurrentIsEquals_1()
    {
        $pagination=new Pagination();
        $pagination->setCurrent(1);
        $this->assertEquals(1, $pagination->getPrevious());
    }
    function test_previous_shouldReturn_1_whenCurrentIsEquals_2()
    {
        $pagination=new Pagination();
        $pagination->setCurrent(2);
        $this->assertEquals(1, $pagination->getPrevious());
    }
    function test_previous_shouldReturn_2_whenCurrentIsEquals_3()
    {
        $pagination=new Pagination();
        $pagination->setCurrent(3);
        $this->assertEquals(2, $pagination->getPrevious());
    }
    
    function test_next_shouldReturn_1_whenPageCountAreNotDefined()
    {
        $pagination=new Pagination();
        $pagination->setCurrent(1);
        $this->assertEquals(1, $pagination->getNext());
    }
    function test_next_shouldReturn_1_whenCurrentAndPageCountAreEquals_1()
    {
        $pagination=new Pagination(1);
        $pagination->setCurrent(1);
        $this->assertEquals(1, $pagination->getNext());
    }
    function test_next_shouldReturn_2_whenCurrentEquals_1_AndPageCountAreEquals_2()
    {
        $pagination=new Pagination();
        $pagination->setCurrent(1);
        $pagination->setTotalPageCount(2);
        $this->assertEquals(2, $pagination->getNext());
    }
    
    function test_getLinks_shouldReturnArrayWithOneElement_whenPaginationHasNotLink()
    {
        $pagination=new Pagination();
        $links=$pagination->getLinks();
        $this->assertEquals(1, sizeof($links));
        $this->assertEquals([1], $links);
    }
    
    function test_getLinks_shouldReturnArrayWithSize_4_whenPaginationContain_4_pages()
    {
        $pagination=new Pagination();
        $pagination->setTotalPageCount(4);
        $pagination->setLimitToGenerate(10);
        $links=$pagination->getLinks();
        $this->assertEquals(4, sizeof($links));
    }
    
    function test_getLinks_shouldReturnArrayWithSize_6_whenPaginationContain_10_pagesAndPAgeLimitIsEquals_6()
    {
        $pagination=new Pagination();
        $pagination->setTotalPageCount(10);
        $pagination->setLimitToGenerate(6);
        $links=$pagination->getLinks();
        $this->assertEquals(6, sizeof($links));
    }
    
    function test_getLinks_shouldReturnArrayWith_3_4_5_whenTheCurrentIs_3_AndMaxPAgeLimitIsEquals_3()//usecase : i clicked on the last page visible
    {
        $pagination=new Pagination();
        $pagination->setTotalPageCount(10);
        $pagination->setLimitToGenerate(3);
        $pagination->setCurrent(3);
        $links=$pagination->getLinks();
        $this->assertEquals([3,4,5], $links);
    }
   
    function test_getLinks_shouldReturnArrayWith_1_2_3_whenTheCurrentIs_2_AndMaxPAgeLimitIsEquals_3() // run through visible page
    {
        $pagination=new Pagination();
        $pagination->setTotalPageCount(10);
        $pagination->setLimitToGenerate(3);
        $pagination->setCurrent(2);
        $links=$pagination->getLinks();
        $this->assertEquals([1,2,3], $links);
    }
    
    function test_getLinks_shouldReturnArrayWith_3_4_5_whenTheCurrentIs_4_AndMaxPAgeLimitIsEquals_3()
    {
        $pagination=new Pagination();
        $pagination->setTotalPageCount(10);
        $pagination->setLimitToGenerate(3);
        $pagination->setCurrent(4);
        $links=$pagination->getLinks();
        $this->assertEquals([3,4,5], $links);
    }
    
    function test_getLinks_shouldReturnArrayWith_6_whenTheCurrentIs_6_AndMaxPAgeLimitIsEquals_3AndPAgeCountEquals6()
    {
        $pagination=new Pagination();
        $pagination->setTotalPageCount(6);
        $pagination->setLimitToGenerate(3);
        $pagination->setCurrent(6);
        $links=$pagination->getLinks();
        $this->assertEquals([4,5,6], $links);
    }
    
    
    function test_limiteToGenerate_shouldBeEqualsOne_whenItsNotSpecified()
    {
        $pagination=new Pagination();
        $this->assertEquals(1, $pagination->getLimitToGenerate());
    }
    function test_limiteToGenerate_shouldBeEqualsOne_whenItsLessOrEquals0()
    {
        $pagination=new Pagination();
        
        $pagination->setLimitToGenerate(0);
        $this->assertEquals(1, $pagination->getLimitToGenerate());
        $pagination->setLimitToGenerate(-1);
        $this->assertEquals(1, $pagination->getLimitToGenerate());
    }
}
