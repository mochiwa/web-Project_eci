<?php
namespace Test\Framework\HtmlBuilder;

use Framework\HtmlBuilder\Attribute;
use Framework\HtmlBuilder\HtmlElement;

class HtmlElementTest extends \PHPUnit\Framework\TestCase
{
    public function test_constructor_shouldTransformUpperCaseToLower(){
    	$element=new HtmlElement('BODY');

    	$this->assertSame('<body></body>', $element->toHtml()); 
    }
    public function test_constructor_shouldRemoveSpecialCharacter(){
    	$element=new HtmlElement('<b<od>y>');

    	$this->assertSame('<body></body>', $element->toHtml()); 
    }
    public function test_toHtml_shouldReturnTagNameInHtmlElementFormat(){
        $element=new HtmlElement('body');

        $this->assertSame('<body></body>', $element->toHtml()); 
    }

    public function test_toHtml_ShouldNotAppendTheEndingTag_whenInLineElement(){
        $element=new HtmlElement('input',true);

        $this->assertSame('<input>', $element->toHtml()); 
    }
    
    public function test_toHtml_shoudSepareEachAttributeBySpace(){
        $element=new HtmlElement('body');

        $element->addAttribute(new Attribute('class'));
        $element->addAttribute(new Attribute('name'));

        $this->assertSame('<body class="" name=""></body>', $element->toHtml()); 
    }

    public function test_toHtml_shouldAppendTheContentAsTextBetweenTag(){
        $element=new HtmlElement('p');
        $element->setContent("Hello world");

        $this->assertSame('<p>Hello world</p>', $element->toHtml());
    }
    
    public function test_toHtml_shouldShouldRemoveHtmlEntitiesToTheTextContentBetweenTag(){
        $element=new HtmlElement('p');
        $element->setContent("He<>llo wor</ld");

        $this->assertNotSame('He<>llo wor</ld', $element->toHtml());
    }

    public function test_toHtml_shouldAppendChildrenBetweenTag(){
        $element=new HtmlElement('body');
        $element->addChild(new HtmlElement('section'));

        $this->assertNotSame('<body><section> </section></body>', $element->toHtml());
    }
    
    
    public function test_addAttribute_shouldAppendAnAttribute(){
        $element=new HtmlElement('body');

        $element->addAttribute(new Attribute('class'));

        $this->assertSame('<body class=""></body>', $element->toHtml()); 
    }
    
    public function test_addAttribute_shouldNotAppendAttribute_whenAttributeIsAlreadyPresent(){
        $element=new HtmlElement('body');

        
        $element->addAttribute(new Attribute('class'));
        $element->addAttribute(new Attribute('class'));

        $this->assertSame('<body class=""></body>', $element->toHtml()); 
    }
    public function test_addAttribute_shouldNotReplaceCurrentAttribute_whenAttributeIsAlreadyPresent(){
        $element=new HtmlElement('body');

        $element->addAttribute(new Attribute('class',['nav'=>true]));
        $element->addAttribute(new Attribute('class'));
        
        $this->assertSame('<body class="nav"></body>', $element->toHtml()); 
    }
    

    public function test_getAttribute_shouldReturnTheAttribute_whenAnAttributeNameMatches()
    {
        $element=new HtmlElement('body');
        $att=new Attribute('class');
        $element->addAttribute($att);

        $this->assertSame($att, $element->getAttribute('class'));
    }
    
    public function test_getAttribute_shouldThrowException_whenAttributeNotFound()
    {
        $element=new HtmlElement('body');
        $this->expectException(\InvalidArgumentException::class);
        $element->getAttribute('anAttribute');
    }
    
    public function test_addStyle_shouldAppendClassToStyleAttribute()
    {
        $element=new HtmlElement('body');
        $element->addAttribute(Attribute::of('class'));
        $element->addStyle('nav');
        
        $this->assertSame('<body class="nav"></body>', $element->toHtml());
    }
    
    public function test_addStyle_shouldCreateStyleArgument_whenIsNotExist()
    {
        $element=new HtmlElement('body');
        $element->addStyle('nav');
        
        $this->assertSame('<body class="nav"></body>', $element->toHtml());
    }
    
    
    public function test_setId_shouldAddAttributeId_whenItIsNotPresent()
    {
        $element=new HtmlElement('body');
        $element->setId('aaa');
        $this->assertSame('aaa', $element->id());
    }
    
    public function test_setId_shouldReplaceTheId_whenItIsAlreadyPresent()
    {
        $element=new HtmlElement('body');
        $element->setId('aaa');
        $element->setId('bbb');
        $this->assertSame('bbb', $element->id());
        $this->assertSame('<body id="bbb"></body>', $element->toHtml());
    }
    
    public function test_getChild_shouldReturnNull_whenChildWithIdNotFound()
    {
        $element=new HtmlElement('body');
        $this->assertNull($element->getChild('notExistingId'));
    }
    
    public function test_getChild_shouldReturnTheChild_whenChildWithIdFound()
    {
        $element=new HtmlElement('body');
        $element->addChild((new HtmlElement('div'))->setId('anId'));
        $this->assertNotNull($element->getChild('anId'));
    }

}


?>