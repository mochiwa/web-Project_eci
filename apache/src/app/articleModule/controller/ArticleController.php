<?php

namespace App\Article\Controller;

use App\Article\Application\IndexApplication;
use App\Article\Application\ReadArticleApplication;
use App\Article\Application\Request\IndexRequest;
use App\Article\Application\Request\ReadArticleRequest;
use App\Article\Controller\IArticleController;
use Framework\Controller\AbstractController;
use Framework\DependencyInjection\IContainer;
use Framework\Renderer\IViewBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
/**
 * Description of ArticleController
 *
 * @author mochiwa
 */
class ArticleController extends AbstractController implements IArticleController{
    const INDEX="/parking/index";
    
    /**
     * the view builder
     * @var IViewBuilder
     */
    private $viewBuilder;
    
    
    public function __construct(IContainer $container) {
        parent::__construct($container);
        $this->viewBuilder=$container->get(IViewBuilder::class);
    }
    
    public function __invoke(RequestInterface $request) : ResponseInterface {
        $action=$request->getAttribute('action');
        
        if(method_exists(IArticleController::class, $action) && is_callable([$this,$action])){
                return call_user_func([$this,$action],$request);
        }
        return $this->redirectTo(self::INDEX);
    }

    public function index(RequestInterface $request): ResponseInterface {
        $appRequest= IndexRequest::of($request->getAttribute('articlePerPage'), $request->getAttribute('page'),'parking.page');
        $appService=$this->container->get(IndexApplication::class);
        $appResponse= call_user_func($appService,$appRequest);
        
        if($appResponse->hasErrors()){
            return $this->redirectTo(self::INDEX,self::BAD_REQUEST);
        }
        
        return $this->buildResponse($this->viewBuilder->build('@article/index',
            ['articles' => $appResponse->getArticles(),
            'pagination'=>$appResponse->getPagination()]));
    }

    public function show(RequestInterface $request): ResponseInterface{
        $appRequest= ReadArticleRequest::fromId($request->getAttribute('id'));
        $appService = $this->container->get(ReadArticleApplication::class);
        $appResponse = call_user_func($appService, $appRequest);

        if ($appResponse->hasErrors()) {
            $this->redirectTo(self::INDEX, self::BAD_REQUEST);
        }
        return $this->buildResponse($this->viewBuilder->build('@article/article', [
            'article' => $appResponse->getArticle()]));


        /*$appRequest=new ShowArticleRequest($request->getAttribute('id'));
        $appService=$this->container->get(ShowArticleApplication::class);
        $appResponse=$appService($appRequest);
        
        if($appResponse->hasErrors()){
            return $this->redirectTo(self::INDEX);
        }
        return $this->buildResponse($this->viewBuilder->build('@article/article',['article'=>$appResponse->getArticle()]));*/
    }
}
