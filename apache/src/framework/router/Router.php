<?php
namespace Framework\Router;

use AltoRouter;
use Psr\Http\Message\RequestInterface;


/**
 * The router is responsible to find and manage all route on website
 *
 * @author mochiwa
 */
class Router {
    
    /**
     * @var AltoRouter 
     */
    private $router;
    
    
    public function __construct() {
        $this->router=new AltoRouter();
    }
    
    /**
     * Try to match a RequestInterface to a know route,
     * if route match and method are same then return a new route
     * with data about it @see Route.
     *
     * @param RequestInterface $request
     * @return \Framework\Router\Route|null
     */
    public function match(RequestInterface $request) : ?Route
    {
        $match=$this->router->match($request->getUri()->getPath(),$request->getMethod());
        if($match){
            return new Route($match['name'],$match['target'],$match['params']);
        }
        return null;
    }
    
    /**
     * Map a link , complex link can be build with these regex:
     *           [i]   // Match an integer
     *           [a]   // Match alphanumeric characters as 'action'
     * Example of link :
     *      /home/article-01   -> /home/[a]-[i]
     *
     * @param string $method The method used POST/GET/
     * @param string $uri The uri used in the navigator
     * @param callable $callback The closure associated with the target
     * @param string $routename the route name
     * 
     * @throws InvalidArgumentException when the method is empty
     * @throws InvalidArgumentException when the uri is empty
     */
    public function map(string $method,string $uri,callable $callback,string $routename=''){
        if(!$method)
            throw new \InvalidArgumentException("The method cannot be empty");
        else if(!$uri)
            throw new \InvalidArgumentException("The uri cannot be empty");
        
        $this->router->map($method,$uri,$callback,$routename);
    }
    
    /**
     * Generate an URL for the route name with optionally parameters.
     * when append parameters these must match with the orignal pattern 
     * example :
     *  original patern : /blog/[a:article]-[i:id]
     *  params'll look like ['article'=>'blog','id'=>'01']
     * 
     * When a route is not found then return #
     * @param string $routeName the route name registered in the map method
     * @param array $params list of params with key => value
     * @return string the URL if route name exist , # else
     */
    public function generateURL(string $routeName,array $params=[]) : string
    {
        try{
            return $this->router->generate($routeName,$params);
        } catch (\RuntimeException $ex) {
            return '#';
        }
    }
}
