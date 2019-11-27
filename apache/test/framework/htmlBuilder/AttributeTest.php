<?php
namespace Test\Framework\HtmlBuilder;

use Framework\HtmlBuilder\Attribute;


class AttributeTest extends \PHPUnit\Framework\TestCase
{
    private $attribute;
    public function setUp(){        
        $this->attribute=new Attribute('class');
    }

    public function tearDown(){}

    public function test_constructor_shouldRemoveSpecialChar(){
    	$attribute=new Attribute('class"></');

    	$this->assertSame('class=""', $attribute->toHtml()); 
    }

    public function test_addContent_shouldAppendTheContentBetweenQuote(){
        $this->attribute->addContent('nav');

        $this->assertSame('class="nav"', $this->attribute->toHtml()); 
    }

    public function test_addContent_ShouldRemoveSpecialHTMLCharOfContent(){
    	$this->attribute->addContent('n<"\'av');

    	$this->assertNotSame('class="n<"\'av', $this->attribute->toHtml()); 
    }
    
    public function test_setContent_ShouldReplaceAllContent(){
    	$this->attribute->addContent('BeforeContent');
        $this->attribute->setContent('AfterContent');

    	$this->assertSame('AfterContent', $this->attribute->value());
    }



    public function test_toHtml_ShouldSepareContentsBySpace(){
    	$this->attribute->addContent('nav')->addContent('nav--hidden');

    	$this->assertSame('class="nav nav--hidden"', $this->attribute->toHtml()); 
    }
    public function test_toHtml_ShouldNotAppendContent_WhenItIsDisable(){
    	$this->attribute->addContent('nav')->addContent('nav--hidden',false);

    	$this->assertSame('class="nav"', $this->attribute->toHtml()); 
    }
    public function test_toHtml_shouldNotAppendContent_WhenClosureReturnFalse(){
    	$this->attribute->addContent('nav')->addContent('nav--hidden',function(){return false;});

    	$this->assertSame('class="nav"', $this->attribute->toHtml()); 
    }
    
    
    public function test_contents_shouldReturnAnArrayWithContentSetted(){
        $this->attribute->addContent('nav')->addContent('nav--hidden',false);
        
        $this->assertSame(array('nav'=> true , 'nav--hidden'=>false), $this->attribute->contents());
    }
    
    public function test_value_shouldReturnAllContentSeparateBySpaceInOneString(){
        
        $this->attribute->addContent('nav')->addContent('nav--hidden');
        $this->assertSame('nav nav--hidden', $this->attribute->value());
    }
    public function test_value_shouldNotReturnDisableContent(){
        $this->attribute->addContent('nav')->addContent('nav--hidden',false);
        $this->assertSame('nav', $this->attribute->value());
    }
}
