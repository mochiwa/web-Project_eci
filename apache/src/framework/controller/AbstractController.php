<?php

namespace Framework\Controller;

use Framework\DependencyInjection\IContainer;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of AbstractController
 *
 * @author mochiwa
 */
abstract class AbstractController {
    /**
     * This constant should be the default action of the controller
     */
    const INDEX="";
    
    /**
     * the dependency injector
     * @var IContainer 
     */
    protected $container;


    public function __construct(IContainer $container) {
        $this->container=$container;
    }
    
    /**
     * This method dispatch the action from the URL , if a method name match
     * with the action then return its result ,
     * If any method find then redirect to the index constant
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public abstract function __invoke(RequestInterface $request) : ResponseInterface;
    
    
    /**
     * Return an Response interface built with the body
     * @param string $body
     * @param int $code
     * @return ResponseInterface
     */
    protected function buildResponse(string $body,int $code=200) : ResponseInterface
    {
        $response=new Response(200);
        $response->getBody()->write($body);
        return $response;
    }
    
    /**
     * Return a response with a redirection header
     * The target must be knew by the router
     * @param string $target
     * @param int $code
     * @return ResponseInterface
     */
    protected function redirectTo(string $target,int $code=200,string $cause='') : ResponseInterface
    {
        return (new Response(200))
            ->withHeader('Location', $target)
            ->withStatus($code, $cause);
    }
    
    /**
     * Return true if the request is a POST request
     * @param RequestInterface $request
     * @return bool
     */
    protected function isPostRequest(RequestInterface $request):bool
    {
        return $request->getMethod()==='POST';
    }
}
