<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\App\Article\Infrastructure\Persistance;

use App\Article\Infrastructure\Persistance\InMemory\AbstractInMemoryFactory;
use PHPUnit\Framework\TestCase;

/**
 * Description of AbstractInMemoryFactoryTest
 *
 * @author mochiwa
 */
class AbstractInMemoryFactoryTest extends TestCase{
    private $factory;
    
    
    function tearDown() {
        if(file_exists(AbstractInMemoryFactory::DIR)){
            if(file_exists(AbstractInMemoryFactory::DIR.'file'))
                unlink (AbstractInMemoryFactory::DIR.'file');
            rmdir(AbstractInMemoryFactory::DIR);
        }
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
