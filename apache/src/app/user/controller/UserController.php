<?php
namespace App\User\Controller;

use Framework\Renderer\IViewBuilder;

/**
 * Description of UserController
 *
 * @author mochiwa
 */
class UserController {
    private $viewBuilder;
    public function __construct(IViewBuilder $viewBuilder) {
        $this->viewBuilder=$viewBuilder;
    }
    
    public function __invoke(\Psr\Http\Message\RequestInterface $request) {
        $response = null;
        
        if($request->getRequestTarget())
        {
            
            $response= $this->helloworld();
        }
        return $response;
    }
    
    
    public function helloworld(): \Psr\Http\Message\ResponseInterface
    {
        $response= new \GuzzleHttp\Psr7\Response();
        $response->getBody()->write($this->viewBuilder->build('@user/register'));
        return $response;
    }
}
