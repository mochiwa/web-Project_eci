<?php

namespace App;

use Psr\Http\Message\RequestInterface;

/**
 * Description of Application
 *
 * @author mochiwa
 */
class Application {
    private $router;
    private $modules;
    
    public function __construct() {
        $this->router=new \Framework\Router\Router();
    }
    
    public function addModule($module): self{
        $this->modules[]=new $module($this->router);
        return $this;
    }
         
    public function run(RequestInterface $request) : \Psr\Http\Message\ResponseInterface{
       $route= $this->router->match($request);
       if(!$route)
       {
           return $this->run(new \GuzzleHttp\Psr7\Request('GET','/404'));
       }
       
       foreach ($route->params() as $key=>$param)
           $request=$request->withAttribute($key,$param);
       
       $response= call_user_func_array($route->target(), [$request]);
       if(is_string($response))
           return new \GuzzleHttp\Psr7\Response(200,[],$response);
       elseif($response instanceof \Psr\Http\Message\ResponseInterface)
           return $response;
       else
           throw new Exception();
       
    }
}
