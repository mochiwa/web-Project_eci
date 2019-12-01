<?php
namespace Test\Framework\HtmlBuilder;

use Framework\HtmlBuilder\Input;
use InvalidArgumentException;

/**
 * Description of InputTest
 *
 * @author mochiwa
 */
class InputTest extends \PHPUnit\Framework\TestCase {
    
   
    
    function test_constructor_shouldThrowException_whenNameTrimmedIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $input=new Input('');
    }
    
    function test_constructor_shouldThrowException_whenTypeTrimmedIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $input=new Input('teste','');
    }
    
    function test_name_shouldReturnTheNameOfTheInputPassedToConstructor()
    {
        $input=new Input('teste');
        $this->assertSame('teste', $input->name());
    }
    
    function test_id_shouldReturnTheNameOfTheInputPassedToConstructor()
    {
        $input=new Input('teste','text','anId');
        $this->assertSame('anId', $input->id());
    }
}
