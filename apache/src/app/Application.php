<?php

namespace App;

use Exception;
use Framework\DependencyInjection\IContainer;
use Framework\Router\IRouter;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * The main application
 *
 * @author mochiwa
 */
class Application {
    /**
     *
     * @var IRouter 
     */
    private $router;
    /**
     *
     * @var array contain all modules 
     */
    private $modules;
    
    /**
     *
     * @var IContainer the dependency container
     */
    private $container;
    
    public function __construct(IContainer $container) {
        $this->container=$container;
        
        $this->router=$this->container->get(IRouter::class);
    }
    
    /**
     * Append a module to the application,
     * If module contain a definition then container append it.
     * @param string $module
     * @return \self
     */
    public function addModule(string $module): self{
        if($module::DEFINITION)
        {
            $this->container->appendDefinition (require_once $module::DEFINITION);
        }
        $this->modules[]=$this->container->get($module);
        return $this;
    }
    
    /**
     * Launch the application
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
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
       {
           return new Response(200,[],$response);
       }
       elseif($response instanceof ResponseInterface)
       {
           return $response;
       }
       else
       {
           throw new \RuntimeException("The application cannot deal with this request.");
       }
       
    }
    
   
}
