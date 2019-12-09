<?php

namespace App\Identity\Controller;

use App\Identity\Application\RegisterUserApplication;
use App\Identity\Application\Request\RegisterUserRequest;
use Framework\DependencyInjection\IContainer;
use Framework\Renderer\IViewBuilder;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of UserController
 *
 * @author mochiwa
 */
class UserController {
    private $viewBuilder;
    private $container;
    function __construct(IContainer $container) {
        $this->container=$container;
        $this->viewBuilder=$container->get(IViewBuilder::class);
    }
    
    
    public function __invoke(RequestInterface $request) : ResponseInterface{
        $action=$request->getAttribute('action');
        
        if(method_exists($this, $action) && is_callable([$this,$action])){
                return call_user_func([$this,$action],$request);
        }
        return $this->redirectToIndex();
    }
    
    private function register(RequestInterface $request): ResponseInterface
    {
        if($request->getMethod()!=='POST')
        {
            $response = new Response(200);
            $response->getBody()->write($this->viewBuilder->build('@user/userRegister'));
            return $response;
        }
        
        $registerUserApplication=$this->container->get(RegisterUserApplication::class);
        $registerResponse=$registerUserApplication(RegisterUserRequest::fromPost($request->getParsedBody()));
        
        if($registerResponse->hasErrors())
        {
            
            $response=new Response(400);
            $response->getBody()->write($this->viewBuilder->build('@user/userRegister',[
                'errors'=>$registerResponse->getErrors(),
                'user'=>$registerResponse->getUserView()]));
            return $response;
        }
        return $this->redirectToIndex();
    }
    
     /**
     * Return a response that redirect to the admin index
     * @param int $code the status code 200 by default
     * @return ResponseInterface
     */
    private function redirectToIndex(int $code=200) : ResponseInterface
    {
        $response = new Response($code);
        return $response->withHeader('Location', '/home');
    }

}
