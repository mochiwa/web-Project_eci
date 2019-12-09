<?php

namespace Framework\Controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of AbstractController
 *
 * @author mochiwa
 */
abstract class AbstractController {
    const INDEX="";
    /**
     * 
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request) : ResponseInterface{
        $action=$request->getAttribute('action');
        
        if(method_exists($this, $action) && is_callable([$this,$action])){
                return call_user_func([$this,$action],$request);
        }
        return $this->redirectTo(self::INDEX);
    }
    
    
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
}
