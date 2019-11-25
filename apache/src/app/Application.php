<?php

namespace App;

use Exception;
use Framework\DependencyInjection\IContainer;
use Framework\Router\Router;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of Application
 *
 * @author mochiwa
 */
class Application {
    private $router;
    private $modules;
    private $viewBuilder;
    private $container;
    
    public function __construct(IContainer $container) {
        $this->container=$container;
        
        $this->router=$this->container->get(Router::class);
    }
    
    public function addModule( $module): self{
        if($module::DEFINITION)
            $this->container->appendDefinition (require_once $module::DEFINITION);
        $this->modules[]=$this->container->get($module);
        return $this;
    }
         
    public function run(RequestInterface $request) : ResponseInterface{
       $route= $this->router->match($request);
       if(!$route)
       {
           return $this->run(new Request('GET','/404'));
       }
       
       foreach ($route->params() as $key=>$param)
       {
           $request=$request->withAttribute($key,$param);
       }
           
       
       if(is_string($route->target()))
       {
          $callback=$this->container->get($route->target());
       } 
       else
       {
          $callback=$route->target(); 
       }
           
       
       $response= call_user_func_array($callback, [$request]);
       
       if(is_string($response))
           return new Response(200,[],$response);
       elseif($response instanceof ResponseInterface)
           return $response;
       else
           throw new Exception();
       
    }
    
    private function buildRequestWithParameters($request,array $params)
    {
        foreach ($route->params() as $key=>$param)
       {
           $request=$request->withAttribute($key,$param);
       }
    }
}
