<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Controller;

use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
use App\Article\Validation\ParkingFormValidator;
use Exception;
use Framework\Renderer\IViewBuilder;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A controller for administration
 *
 * @author mochiwa
 */
class AdminArticleController {
    private $viewBuilder;
    private $repository;
    
    function __construct(IViewBuilder $viewBuilder, IArticleRepository $repository) {
        $this->viewBuilder=$viewBuilder;
        $this->repository=$repository;
    }
    
    public function __invoke(RequestInterface $request) {
        if(strpos($request->getRequestTarget(), 'create'))
        {
            return $this->createArticle($request);
        }
        return $this->index();
    }
    
    private function index(){
       $response=new Response(200);
       $data =$this->repository->All();
       $response->getBody()->write($this->viewBuilder->build('@article/index',['data'=>$data]));
       return $response;
    }
    
    private function createArticle(RequestInterface $request): ResponseInterface
    {
       $response=new Response(200);
       
           
       if($request->getMethod()==='POST')
       {
           $postData=$request->getParsedBody();
           $validator=new ParkingFormValidator($postData);
           if(!$validator->isValid())
           {
               return $this->responseWithErrors('@article/createArticle', $validator->getErrors());
           }
           
           //disable auto commit
           try{
               $request=CreateArticleRequest::fromArray($postData);
               
               $service = new CreateArticleService($this->repository);
               $service->execute($request);
               return $response->withHeader('Location', '/parking/admin');
           } catch (Exception $error) {
               return $this->responseWithErrors('@article/createArticle', ['title'=>[$error->getMessage()]]);
           }
       }
       $response->getBody()->write($this->viewBuilder->build('@article/createArticle'));
       return $response;
    }
    
    
    private function responseWithErrors(string $view,$errors,int $status=406)
    {
        $response=new Response($status);
        $response->getBody()->write($this->viewBuilder->build($view,['errors'=>$errors]));
        return $response;
    }
}
