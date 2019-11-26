<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Controller;

use Framework\Renderer\IViewBuilder;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of AdminArticleController
 *
 * @author mochiwa
 */
class AdminArticleController {
    private $viewBuilder;
    
    function __construct(IViewBuilder $viewBuilder) {
        $this->viewBuilder=$viewBuilder;
    }
    
    public function __invoke(RequestInterface $request) {
        return $this->createArticle($request);
    }
    
    private function createArticle(RequestInterface $request): ResponseInterface
    {
       $response=new Response(200);
       $data;
       if($request->getMethod()==='POST')
           $data['text']="hello world";
       
       $response->getBody()->write($this->viewBuilder->build('@article/createArticle',$data));
       return $response;
    }
}
