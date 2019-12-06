<?php

namespace App\Article\Controller;

use App\Article\Application\Service\CreateArticleApplication;
use App\Article\Application\Service\DeleteArticleApplication;
use App\Article\Application\Service\EditArticleApplication;
use App\Article\Application\Service\IndexArticleApplication;
use Exception;
use Framework\DependencyInjection\IContainer;
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
    private $container;
    function __construct(IContainer $container) {
        $this->container=$container;
        $this->viewBuilder=$container->get(IViewBuilder::class);
    }

    public function __invoke(RequestInterface $request) {
        $action=$request->getAttribute('action');
        
        if(method_exists($this, $action) && is_callable([$this,$action])){
                return call_user_func([$this,$action],$request);
        }
        return $this->redirectToIndex();
    }

    private function index(RequestInterface $request) : ResponseInterface{
        $appService=$this->container->get(IndexArticleApplication::class);
        $appResponse=$appService->execute($request->getAttribute('page') ?? '1');
        
        $httpResponse=new Response(200);
        $httpResponse->getBody()->write($this->viewBuilder->build('@article/index',
            ['articles' => $appResponse->getArticles(),
            'pagination'=>$appResponse->getPagination()]));
        return $httpResponse;
    }
    
    
    public function create(RequestInterface $request): ResponseInterface{
        if($request->getMethod()!=='POST')
        {
            $response = new Response(200);
            $response->getBody()->write($this->viewBuilder->build('@article/createArticle'));
            return $response;
        }
        
        $post = $request->getParsedBody();
        $post['picture'] = $this->extractPictureFromRequest($request,'picture');
        $service = $this->container->get(CreateArticleApplication::class);
        $response = $service($post);
        
        if($response->hasErrors())
        {
            return $this->responseWithErrors('@article/createArticle',
                ['errors'=>$response->getErrors(),'article'=>$response->getArticle()]);
        }
        return $this->redirectToIndex(200);
    }
    
   
    
    private function edit(RequestInterface $request) : ResponseInterface
    {
        $post = $request->getParsedBody();
        $id=$request->getAttribute('id');
        $service = $this->container->get(EditArticleApplication::class);
        $response=$service($id,$post);
        
        if($response->isEdited() || $response->isArticleNotFound()){
            return $this->redirectToIndex();
        }
        return $this->responseWithErrors('@article/editArticle', ['errors' => $response->getErrors(),'article'=>$response->getArticle()]);
    }
     
    
    private function delete(RequestInterface $request)
    {
        $service=$this->container->get(DeleteArticleApplication::class);
        
        $response=$service->execute($request->getAttribute('id'));
        if($response->hasErrors())
        {
            return $this->redirectToIndex(400);
        }
        return $this->redirectToIndex();
    }
    
    

     /**
     * Extract a file path from a request, if file not found then return empty string
     * @param RequestInterface $request
     * @return string
     */
    private function extractPictureFromRequest(RequestInterface $request,string $field): string {
        try {
            return $request->getUploadedFiles()[$field]->getStream()->getMetadata('uri');
        } catch (Exception $ex) {
            
        }
        return '';
    }
    
    /**
     * Return a response with one or many errors
     * @param string $view
     * @param type $errors
     * @param int $status
     * @return Response
     */
    private function responseWithErrors(string $view, $errors, int $status = 400,string $cause=''):ResponseInterface {
        $response = new Response();
        $response->getBody()->write($this->viewBuilder->build($view,  $errors ));
        return $response->withStatus($status, $cause);
    }

    /**
     * Return a response that redirect to the admin index
     * @param int $code the status code 200 by default
     * @return ResponseInterface
     */
    private function redirectToIndex(int $code=200) : ResponseInterface
    {
        $response = new Response($code);
        return $response->withHeader('Location', '/parking/admin/index');
    }
}
