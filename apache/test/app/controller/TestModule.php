<?php
namespace Test\App;

use Psr\Http\Message\RequestInterface;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestModule
 *
 * @author mochiwa
 */
class TestModule {
   public function __construct(\Framework\Router\Router $router) {
        $router->map('GET', '/module', [$this,'moduleExec'],'module.exe');
    }
    
    public function moduleExec(RequestInterface $request)
    {
        return new \GuzzleHttp\Psr7\Response();
    }
}
