<?php
namespace Test\Framework\DependencyInjection;
require_once('testClasses/Foo.php');
require_once('testClasses/Faa.php');
require_once('testClasses/FaaFoo.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DependencyInjectionTest
 *
 * @author mochiwa
 */
class ContainerTest extends \PHPUnit\Framework\TestCase {
    
    private $container;
    public function setUp() {
        $this->container=new \Framework\DependencyInjection\Container();
    }
    
    public function test_getShoulResolveClass_whenKeyNotFoundInContainer()
    {
        $this->assertNotNull($this->container->get(Foo::class));
    }
    
    public function test_get_shouldReturnAInstanceOfClass_whenClassHasBeenSetted()
    {
        $this->container->set(Foo::class, function(){return new Foo();});
        $this->assertTrue($this->container->get(Foo::class) instanceof Foo);
    }
    public function test_get_shouldReturnTheSameInstance_whenItAlreadyCreated()
    {
        $this->container->set(Foo::class, function(){return new Foo();});
        $idExpected=$this->container->get(Foo::class)->id();
        $idActual=$this->container->get(Foo::class)->id();
        
        $this->assertSame($idExpected,$idActual);
    }
    
    public function test_make_shoulThrow_whenKeyNotFoundInContainer()
    {
        $this->expectException(\Psr\Container\ContainerExceptionInterface::class);
        $this->container->make('notExisitng');
    }
    
    public function test_make_shouldReturnANewInstanceEachTime()
    {
        $this->container->set(Foo::class, function(){return new Foo();});
        $idExpected=$this->container->make(Foo::class)->id();
        $idActual=$this->container->make(Foo::class)->id();
        
        $this->assertNotSame($idExpected,$idActual);
    }
    
    public function test_appendDefinition_shouldSetEachArgument()
    {
        echo $this->container->appendDefinition(require('testClasses/config.php'));
        $this->assertNotNull($this->container->get(Foo::class));
        $this->assertNotNull($this->container->get(Faa::class));
        $this->assertNotNull($this->container->get(FaaFoo::class));
    }
   
    
    
}
