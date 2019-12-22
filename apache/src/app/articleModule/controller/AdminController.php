<?php

namespace App\Article\Controller;

use App\Article\Application\CreateArticleApplication;
use App\Article\Application\DeleteArticleApplication;
use App\Article\Application\IndexApplication;
use App\Article\Application\ReadArticleApplication;
use App\Article\Application\Request\CreateArticleRequest;
use App\Article\Application\Request\DeleteArticleRequest;
use App\Article\Application\Request\IndexRequest;
use App\Article\Application\Request\ReadArticleRequest;
use Framework\Controller\AbstractCRUDController;
use Framework\DependencyInjection\IContainer;
use Framework\FileManager\FileUploadFormater;
use Framework\Renderer\IViewBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A controller for administration
 *
 * @author mochiwa
 */
class AdminController extends AbstractCRUDController {
    const INDEX="/admin/parking/index";
    
    /**
     * @var IViewBuilder
     */
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

    

    protected function index(RequestInterface $request) : ResponseInterface{
        $appRequest= IndexRequest::of($request->getAttribute('articlePerPage'), $request->getAttribute('page'),'admin.parking.page');
        $appService=$this->container->get(IndexApplication::class);
        $appResponse= call_user_func($appService,$appRequest);
        
        if($appResponse->hasErrors()){
            return $this->redirectTo(self::INDEX,self::BAD_REQUEST);
        }
        
        return $this->buildResponse($this->viewBuilder->build('@article/admin/index',
            ['articles' => $appResponse->getArticles(),
            'pagination'=>$appResponse->getPagination()]));
    }
    
    
    protected function create(RequestInterface $request): ResponseInterface{
        
       if(!$this->isPostRequest($request)){
           return $this->buildResponse($this->viewBuilder->build('@article/admin/create'));
       } 
       return $this->createArticleProcess($request);
    }
    
    private function createArticleProcess(RequestInterface $request): ResponseInterface{
        $postData=$request->getParsedBody();
        $postData['picture']=FileUploadFormater::of($request->getUploadedFiles())->pathOf('picture');
        
        $appRequest= CreateArticleRequest::fromPostRequest($postData);
        $appService= $this->container->get(CreateArticleApplication::class);
        $appResponse= call_user_func($appService,$appRequest);
        
        if($appResponse->hasErrors())
        {
            return $this->buildResponse($this->viewBuilder->build('@article/admin/create',[
                'errors'=>$appResponse->getErrors(),
                'article'=>$appResponse->getArticle()]), self::BAD_REQUEST);
        }
        return $this->redirectTo(self::INDEX);
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
    
    protected function update(RequestInterface $request): ResponseInterface {
        if(!$this->isPostRequest($request)){
            $appRequest= ReadArticleRequest::fromId($request->getAttribute('id'));
            $appService= $this->container->get(ReadArticleApplication::class);
            $appResponse= call_user_func($appService,$appRequest);
            
            if($appResponse->hasErrors()){
                $this->redirectTo(self::INDEX, self::BAD_REQUEST);
            }
            return $this->buildResponse($this->viewBuilder->build('@article/admin/edit',[
                'article'=>$appResponse->getArticle()
            ]));
        } 
        return $this->udpateProcess($request);
    }
    
    private function udpateProcess(RequestInterface $request): ResponseInterface{
        
        $appRequest= UpdateArticleRequest::of($request->getParsedBody());
        $appService= $this->container->get(ReadArticleApplication::class);
        $appResponse= call_user_func($appService,$appRequest);
        
        
        if($appResponse->hasErrors()){   
            return $this->buildResponse($this->viewBuilder->build('@article/admin/edit',[
                'article'=>$appResponse->getArticle(),
                'errors' =>$appResponse->getErrors();
            ]));
        }
        
        $this->redirectTo(self::INDEX, self::OK);
    }
    
    protected function delete(RequestInterface $request) : ResponseInterface {
        $appRequest= DeleteArticleRequest::of($request->getAttribute('id'));
        $appService= $this->container->get(DeleteArticleApplication::class);
        $appResponse= call_user_func($appService,$appRequest);
        
        return $this->redirectTo(self::INDEX, $appResponse->hasErrors() ? self::BAD_REQUEST : self::OK);

    }
    

    


    
    
    
    protected function read(RequestInterface $request): ResponseInterface{
        
    }

    

}
