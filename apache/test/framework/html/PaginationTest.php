<?php

namespace Test\Framework\html;

use Framework\Html\Factory\DefaultPaginationFactory;
use Framework\Html\Pagination;
use Framework\Router\IRouter;
use PHPUnit\Framework\TestCase;

/**
 * Description of PaginationTest
 *
 * @author mochiwa
 */
class PaginationTest extends TestCase{
    private $router;
    private $paginationFactory;
    private $pagination;
    
    protected function setUp() {
        $this->router=$this->createMock(IRouter::class);
        $this->router->expects($this->any())->method('generateURL')->will($this->returnArgument(0));
        $this->paginationFactory=new DefaultPaginationFactory($this->router);
        
        $this->pagination=new Pagination($this->paginationFactory);
    }
 
    
    function test_toHtml_shouldGenerateAnPaginationWithOnePage_whenPageCountNotSetted()
    {
        $this->assertEquals(
                '<div>'
                    . $this->paginationFactory->toPrevious('#')->toHtml() 
                    . $this->paginationFactory->currentPage('1')->toHtml()
                    . $this->paginationFactory->toNext('#')->toHtml()
                . '</div>',
                $this->pagination->toHtml());
    }
    
    function test_toHtml_shouldNotGenerateLinkForThePage_whenTheCurrentPageIsThePaginedLink()
    {
        $this->pagination->setCurrentPage(2);
        $this->pagination->setPageCount(10);
        $this->assertContains($this->paginationFactory->currentPage('2')->toHtml(),$this->pagination->toHtml());
        $this->assertNotContains($this->paginationFactory->page('2')->toHtml(),$this->pagination->toHtml());
    }
    
    function test_toHtml_shouldGenerate10Link_whenPaginatorContain10Page()
    {
        $this->pagination->setPageCount(10);
        for($i=1;$i!=10;++$i)
        {
            $this->assertContains($this->paginationFactory->page($i)->toHtml(),$this->pagination->toHtml());
        }
    }
    
    public function test_toPrevious_shouldNotHaveALink_whenCurrentEquals1()
    {
        $this->pagination->setCurrentPage(1);
        $this->assertContains($this->paginationFactory->toPrevious('#')->toHtml(), $this->pagination->toHtml());
    }
    public function test_toPrevious_shouldNotHaveALink_whenCurrentIsNotSet()
    {
        $this->assertContains($this->paginationFactory->toPrevious('#')->toHtml(), $this->pagination->toHtml());
    }  
    public function test_toPrevious_shouldHaveALinkToPage1_whenCurrentEquals2()
    {
        $this->pagination->setCurrentPage(2);
        $this->assertContains($this->paginationFactory->toPrevious('1')->toHtml(), $this->pagination->toHtml());
    }
    
    public function test_toNext_shouldNotHaveALink_whenCurrentEqualsThePageCount()
    {
        $this->pagination->setCurrentPage(1);
        $this->pagination->setPageCount(1);
        $this->assertContains($this->paginationFactory->toNext('#')->toHtml(), $this->pagination->toHtml());
    }
    public function test_toNext_shouldNotHaveALink_whenCurrentIsNotSet()
    {
        $this->assertContains($this->paginationFactory->toNext('#')->toHtml(), $this->pagination->toHtml());
    }  
    public function test_toNext_shouldHaveALinkToPage2_whenCurrentEquals1()
    {
        $this->pagination->setCurrentPage(1);
        $this->pagination->setPageCount(2);
        $this->assertContains($this->paginationFactory->toNext('2')->toHtml(), $this->pagination->toHtml());
    }
    
    public function test_toHtml_shouldGenerateMaximal10Link_whenPageLimitIsNotSpecified()
    {
        $this->pagination->setPageCount(12);
        for($i=1;$i!=10;++$i)
        {
            $this->assertContains($this->paginationFactory->page($i)->toHtml(),$this->pagination->toHtml());
        }
        $this->assertNotContains($this->paginationFactory->page(11)->toHtml(),$this->pagination->toHtml());
        $this->assertNotContains($this->paginationFactory->page(12)->toHtml(),$this->pagination->toHtml());
    }
    
    public function test_toHtml_shouldGenerateMaximal3_whenPageLimitIsSpecifiedTo3()
    {
        $this->pagination->setPageCount(5);
        $this->pagination->setPageLimite(3);
        for($i=1;$i!=3;++$i)
        {
            $this->assertContains($this->paginationFactory->page($i)->toHtml(),$this->pagination->toHtml());
        }
        $this->assertNotContains($this->paginationFactory->page(4)->toHtml(),$this->pagination->toHtml());
        $this->assertNotContains($this->paginationFactory->page(5)->toHtml(),$this->pagination->toHtml());
    }
    
    public function test_toHtml_shouldGenerateLinks3_4_5_whenLimitIs3AndCurrentIs2()
    {
        $this->pagination->setPageCount(5);
        $this->pagination->setPageLimite(3);
        $this->pagination->setCurrentPage(3);
        $this->assertContains($this->paginationFactory->currentPage(3)->toHtml(),$this->pagination->toHtml());
        
        
        for($i=4;$i!=5;++$i)
        {
            $this->assertContains($this->paginationFactory->page($i)->toHtml(),$this->pagination->toHtml());
        }
        
        $this->assertNotContains($this->paginationFactory->page(1)->toHtml(),$this->pagination->toHtml());
        $this->assertNotContains($this->paginationFactory->page(6)->toHtml(),$this->pagination->toHtml());
    }
    
    
    function test_getLocalLimit_shouldReturn1_whenPageCountIsEqualsOne()
    {
        $this->pagination->setPageCount(1);
        $this->assertEquals(1, $this->pagination->getLocalLimit());
    }
    
    function test_getLocalLimit_shouldReturn4_whenPageCountIsEquals8AndActualEquals2AndPageLimitEquals4()
    {
        $this->pagination->setPageCount(8);
        $this->pagination->setPageLimite(4);
        $this->pagination->setCurrentPage(2);
        $this->assertEquals(4, $this->pagination->getLocalLimit());
    }
    
    function test_getLocalLimit_shouldReturn7_whenPageCountIsEquals7AndActualEquals6AndPageLimitEquals4()
    {
        $this->pagination->setPageCount(7);
        $this->pagination->setPageLimite(4);
        $this->pagination->setCurrentPage(6);
        $this->assertEquals(7, $this->pagination->getLocalLimit());
    }
    
    
    
    
    
}
