<?php
namespace Test\Framework\HtmlBuilder;

use Framework\HtmlBuilder\Link;

/**
 * Description of LinkTest
 *
 * @author mochiwa
 */
class LinkTest extends \PHPUnit\Framework\TestCase{
    
    function test_toHtml_shouldReturnAHtmlLink()
    {
        $link=new Link('teste','myLink');
        $this->assertSame('<a href="teste">myLink</a>', $link->toHtml());
    }
    
    function test_target_shouldReturnTheTargetSpecifiedInConstructor()
    {
        $link=new Link('https://google.com','myLink');
        $this->assertSame('https://google.com', $link->target());
    }
    function test_toHtml_shouldReturnTheLinkWithStyleClassSpecifiedInConstructor()
    {
        $style=array();
        $style['nav']=null;
        $style['nav--hidden']=function(){return false;};
        $link=new Link('teste','myLink',$style);
        $this->assertSame('<a href="teste" class="nav">myLink</a>', $link->toHtml());
    }
}
