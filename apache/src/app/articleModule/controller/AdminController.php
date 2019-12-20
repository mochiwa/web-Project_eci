<?php

namespace App\Article\Controller;

use App\Article\Application\IndexApplication;
use App\Article\Application\Request\IndexRequest;
use App\Article\Application\Service\CreateArticleApplication;
use App\Article\Application\Service\DeleteArticleApplication;
use App\Article\Application\Service\EditArticleApplication;
use Exception;
use Framework\Controller\AbstractCRUDController;
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
class AdminController extends AbstractCRUDController {
    const INDEX="/admin/parking/";
    
    
    private $viewBuilder;
    
    function __construct(IContainer $container) {
        parent::__construct($container);
        $this->viewBuilder=$container->get(IViewBuilder::class);
    }

    public function __invoke(RequestInterface $request) : ResponseInterface{
        $action=$request->getAttribute('action');
        
        if(method_exists(AbstractCRUDController::class, $action) && is_callable([$this,$action])){
                return call_user_func([$this,$action],$request);
        }
        return $this->redirectTo(self::INDEX);
    }

    
    
    /*
     * $appRequest=RegisterUserRequest::fromPost($request->getParsedBody());
        $appService=$this->container->get(RegisterUserApplication::class);
        $appResponse= call_user_func_array($this->atomicOperator,[$appService,[$appRequest]]);
        
        if($appResponse->hasErrors())
        {
            $body=$this->viewBuilder->build('@user/register',[
                'errors'=>$appResponse->getErrors(),
                'user'=>$appResponse->getUserView()
            ]);
            return $this->buildResponse($body, 400);
        }
        return $this->requestActivation($appResponse->getUserView()->getUserName());
     */
    
    
    protected function index(RequestInterface $request) : ResponseInterface{
        $appRequest= IndexRequest::of($request->getAttribute('articlePerPage'), $request->getAttribute('page'),'parking.admin.page');
        $appService=$this->container->get(IndexApplication::class);
        $appResponse= call_user_func($appService,$appRequest);
        
        return $this->buildResponse($this->viewBuilder->build('@article/admin/index',
            ['articles' => $appResponse->getArticles(),
            'pagination'=>$appResponse->getPagination()]));
    }
    
    
    protected function create(RequestInterface $request): ResponseInterface{
       /* if($request->getMethod()!=='POST')
        {
            $response = new Response(200);
            $response->getBody()->write($this->viewBuilder->build('@article/admin/createArticle'));
            return $response;
        }
        
        $post = $request->getParsedBody();
        $post['picture'] = $this->extractPictureFromRequest($request,'picture');
        $service = $this->container->get(CreateArticleApplication::class);
        $response = $service($post);
        
        if($response->hasErrors())
        {
            return $this->responseWithErrors('@article/admin/createArticle',
                ['errors'=>$response->getErrors(),'article'=>$response->getArticle()]);
        }
        return $this->redirectToIndex(200);*/
    }
    
   
    
    protected function edit(RequestInterface $request) : ResponseInterface
    {
       /* $post = $request->getParsedBody();
        $id=$request->getAttribute('id');
        $service = $this->container->get(EditArticleApplication::class);
        $response=$service($id,$post);
        
        if($response->isEdited() || $response->isArticleNotFound()){
            return $this->redirectToIndex();
        }
        return $this->responseWithErrors('@article/admin/editArticle', ['errors' => $response->getErrors(),'article'=>$response->getArticle()]);*/
    }
     
    
    protected function delete(RequestInterface $request) : ResponseInterface
    {
     /*   $service=$this->container->get(DeleteArticleApplication::class);
        
        $response=$service($request->getAttribute('id'));
        if($response->hasErrors())
        {
            return $this->redirectToIndex(400);
        }
        return $this->redirectToIndex();*/
    }
    
    

     /**
     * Extract a file path from a request, if file not found then return empty string
     * @param RequestInterface $request
     * @return string
     */
    private function extractPictureFromRequest(RequestInterface $request,string $field): string {
       /* try {
            return $request->getUploadedFiles()[$field]->getStream()->getMetadata('uri');
        } catch (Exception $ex) {
            
        }
        return '';*/
    }
    
    /**
     * Return a response with one or many errors
     * @param string $view
     * @param type $errors
     * @param int $status
     * @return Response
     */
    private function responseWithErrors(string $view, $errors, int $status = 400,string $cause=''):ResponseInterface {
      /*  $response = new Response();
        $response->getBody()->write($this->viewBuilder->build($view,  $errors ));
        return $response->withStatus($status, $cause);*/
    }

    /**
     * Return a response that redirect to the admin index
     * @param int $code the status code 200 by default
     * @return ResponseInterface
     */
   /* private function redirectToIndex(int $code=200) : ResponseInterface
    {
        $response = new Response($code);
        return $response->withHeader('Location', '/admin/parking/index');
    }*/

    
    
    
    protected function read(RequestInterface $request): ResponseInterface{
        
    }

    protected function udpate(RequestInterface $request): ResponseInterface {
        
    }

}
