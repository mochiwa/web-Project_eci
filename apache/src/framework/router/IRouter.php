<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\Router;

use Psr\Http\Message\RequestInterface;

/**
 * Description of IRouter
 *
 * @author mochiwa
 */
interface IRouter {
    /**
     * @param RequestInterface $request
     * @return \Framework\Router\Route|null
     */
    function match(RequestInterface $request): ?Route ;
    /**
     * 
     * @param string $method
     * @param string $uri
     * @param callable $callback
     * @param string $routename
     * @return \self
     */
    function map(string $method,string $uri,callable $callback,string $routename=''):self;
    
    /**
     * 
     * @param string $routeName
     * @param array $params
     * @return string
     */
    function generateURL(string $routeName,array $params=[]) : string;
}
