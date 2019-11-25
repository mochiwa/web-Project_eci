<?php
namespace App\Error;
use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
/**
 * Description of ErrorModule
 *
 * @author mochiwa
 */
class ErrorModule extends \Framework\Module\AbstractModule{
    public function __construct(Router $router) {
        $router->map('GET', '/404', [$this,'pageNotFound'],'error.404');
    }
    
    
    public function pageNotFound(RequestInterface $request) : ResponseInterface
    {
        $response =new Response();
        $response->getBody()->write('404 not found !');
        return $response->withStatus(404);
    }
}
