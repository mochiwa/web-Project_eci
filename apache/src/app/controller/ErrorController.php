<?php

namespace App\Controller;

use Framework\Router\Router;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of ErrorController
 *
 * @author mochiwa
 */
class ErrorController {
    public function __construct(Router $router) {
        $router->map('GET', '/404', [$this,'pageNotFound'],'error.404');
    }
    
    public function pageNotFound(RequestInterface $request) : ResponseInterface
    {
        $response =new \GuzzleHttp\Psr7\Response();
        $response->getBody()->write('404 not found !');
        return $response->withStatus(404);
    }
}
