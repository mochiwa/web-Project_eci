<?php

use App\Article\Model\Article\Title;
use PHPUnit\Framework\TestCase;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticleTitleTest
 *
 * @author mochiwa
 */
class ArticleTitleTest extends TestCase {
    
    
    public function test_constructor_shouldThrowInvalidArgumentException_whenTitleLenghtIsLessThan3()
    {
        $this->expectException(InvalidArgumentException::class);
        Title::of('aa');
    }
    public function test_constructor_shouldThrowInvalidArgumentException_whenTitleLenghtIsSupThan50()
    {
        $this->expectException(InvalidArgumentException::class);
        $title="";
        for($i=0;$i<55;$i++){
            $title.="a";
        }
        Title::of($title);
    }
    
}
