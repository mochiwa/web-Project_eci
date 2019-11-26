<?php
namespace Test\Framework\HtmlBuilder;

use Framework\HtmlBuilder\Li;
use Framework\HtmlBuilder\Ul;
use PHPUnit\Framework\TestCase;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UlTest
 *
 * @author mochiwa
 */
class UlTest extends TestCase{
    
    
    function test_toHtml_shouldReturnAnEmptyUl()
    {
        $ul=new Ul();
        $this->assertSame('<ul></ul>',$ul->toHtml());
    }
    
    function test_addLi_shouldAppendAnEmptyLiBetweenUlTag()
    {
        $ul=new Ul();
        $ul->addLi(new Li());
        $this->assertSame('<ul><li></li></ul>',$ul->toHtml());
    }
    
    function test_addLi_shouldAppendLiWithContentBetweenUlTag()
    {
        $ul=new Ul();
        $ul->addLi(new Li('A content'));
        $this->assertSame('<ul><li>A content</li></ul>',$ul->toHtml());
    }
}
