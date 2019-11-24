<?php

namespace App\Controller;

use Framework\Renderer\ViewBuilder;
use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of UserControoler
 *
 * @author mochiwa
 */
class UserController {
    private $viewBuilder;
    
    public function __construct(Router $router, ViewBuilder $viewBuilder) {
        $this->viewBuilder=$viewBuilder;
        $this->viewBuilder->addPath('user', __DIR__.'/../view');
        
        $router->map('GET', '/signUp', [$this,'signUp'], 'user.signUp');
        $router->map('GET', '/user-[a:name]-[i:id]', [$this,'userInfo'], 'user.info');
    }
    
    public function signUp(RequestInterface $request): ResponseInterface
    {
        $response =new Response();
        $response->getBody()->write($this->viewBuilder->build('@user/signUp'));
        return $response;
    }
    
    public function userInfo(RequestInterface $request):ResponseInterface
    {
        return $response;
    }
    
}
