<?php

use PHPUnit\Framework\TestCase;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AtttributeTest
 *
 * @author mochiwa
 */
class AtttributeTest  extends TestCase {
    
    function test_constructor_shouldThrowInvalidArgumentException_whenKeyLenghtIsLessThan3()
    {
        $this->expectException(\InvalidArgumentException::class);
        App\Article\Model\Article\Attribute::of('k','value');
    }

    
}
