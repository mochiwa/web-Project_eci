<?php

namespace App\Controller;
use Framework\Router\Router;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of UserControoler
 *
 * @author mochiwa
 */
class UserController {
    
    
    public function __construct(Router $router) {
        $router->map('GET', '/signUp', [$this,'signUp'], 'user.signUp');
        $router->map('GET', '/user-[a:name]-[i:id]', [$this,'userInfo'], 'user.info');
    }
    
    public function signUp(RequestInterface $request):ResponseInterface
    {
        $response=new \GuzzleHttp\Psr7\Response(200);
        $response->getBody()->write("Hello world from User Controller");
        return $response;
    }
    
    public function userInfo(RequestInterface $request):ResponseInterface
    {
        echo $request->getAttribute('id');
        echo $request->getAttribute('name');
        $response=new \GuzzleHttp\Psr7\Response(200);
        return $response;
    }
    
}
