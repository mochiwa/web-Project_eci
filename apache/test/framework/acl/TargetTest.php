<?php

use Framework\Acl\AbstractTarget;
use PHPUnit\Framework\TestCase;

/**
 * Description of TargetTest
 *
 * @author mochiwa
 */
class TargetTest extends TestCase{
    
    
    function test_targetUrl_shouldReturnTrueWhenTwoTargetHasTheSameName(){
        $target_a= AbstractTarget::URL('aName');
        $target_b= AbstractTarget::URL('aName');
    
        $this->assertTrue($target_a->isMatch($target_b));
    }
    function test_targetUrl_shouldReturnFalse_WhenTwoTargetHasNotTheSameName(){
        $target_a= AbstractTarget::URL('aName');
        $target_b= AbstractTarget::URL('another');
    
        $this->assertFalse($target_a->isMatch($target_b));
    }
    
    
    function test_TargetController_shouldReturnFalse_whenBothControllerAreNotEquals(){
        $target_a= AbstractTarget::Controller('aName');
        $target_b= AbstractTarget::Controller('another');
        
        $this->assertFalse($target_a->isMatch($target_b));
    }
    function test_TargetController_shouldReturnTrue_whenBothControllerAreEquals(){
        $target_a= AbstractTarget::Controller('aName');
        $target_b= AbstractTarget::Controller('aName');
        
        $this->assertTrue($target_a->isMatch($target_b));
    }
    
    function test_TargetController_shouldReturnFalse_whenBothActionAreNotEquals(){
        $target_a= AbstractTarget::ControllerAction('aName','anAction');
        $target_b= AbstractTarget::ControllerAction('aName','anotherAction');
        
        $this->assertFalse($target_a->isMatch($target_b));
    }
    function test_TargetController_shouldReturnTrue_whenBothActionAreEquals(){
        $target_a= AbstractTarget::ControllerAction('aName','anAction');
        $target_b= AbstractTarget::ControllerAction('aName','anAction');
        
        $this->assertTrue($target_a->isMatch($target_b));
    }
    
    function test_TargetController_shouldReturnTrue_whenControllerHasWildcardHasAction(){
        $target_a= AbstractTarget::ControllerAction('aName', \Framework\Acl\ControllerTarget::WILDCARD);
        $target_b= AbstractTarget::ControllerAction('aName','anAction');
        
        $this->assertTrue($target_a->isMatch($target_b));
    }
    function test_TargetController_shouldReturnFalse_whenControllerInInputHasWildcardHasAction(){
        $target_a= AbstractTarget::ControllerAction('aName', 'anAction');
        $target_b= AbstractTarget::ControllerAction('aName',\Framework\Acl\ControllerTarget::WILDCARD);
        
        $this->assertFalse($target_a->isMatch($target_b));
    }
    
    function test_isMatch_shouldReturnFalse_whenUrlTagetAndControllerTargetCompared(){
         $target_a= AbstractTarget::ControllerAction('aName','anAction');
         $target_b=  AbstractTarget::URL('aName');
         
         $this->assertFalse($target_a->isMatch($target_b));
         $this->assertFalse($target_b->isMatch($target_a));
    }
}
