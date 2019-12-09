<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\App\Shared\Infrastructure;

use App\Shared\Infrastructure\AbstractInMemoryFactory;
use PHPUnit\Framework\TestCase;

/**
 * Description of AbstractInMemoryFactoryTest
 *
 * @author mochiwa
 */
class AbstractInMemoryFactoryTest extends TestCase{
    private $factory;
    
    
    function tearDown() {
          
        $this->factory->clear();
        
    }
    
    function test_constructor_shouldCreateDirectory_inMemory_whenDirectoryNotExist()
    {
        $this->factory=new AbstractInMemoryFactory("file");
        $this->assertTrue(file_exists(AbstractInMemoryFactory::DIR));
    }
    
    function test_constructor_shouldCreateFileInArg_whenItNotExist()
    {
        $this->factory=new AbstractInMemoryFactory("file");
        $this->assertTrue(file_exists(AbstractInMemoryFactory::DIR.'file'));
    }
    

    
    
}
