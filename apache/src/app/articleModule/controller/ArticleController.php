<?php

namespace App\Article\Controller;

use App\Article\Application\Service\IndexArticleApplication;
use App\Article\Application\Service\Request\ShowArticleRequest;
use App\Article\Application\Service\ShowArticleApplication;
use App\Article\Controller\IArticleController;
use Framework\Controller\AbstractController;
use Framework\DependencyInjection\IContainer;
use Framework\Renderer\IViewBuilder;
use GuzzleHttp\Psr7\Response;
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
        $appService=$this->container->get(IndexArticleApplication::class);
        $appResponse=$appService->execute($request->getAttribute('page') ?? '1',10);
        
        $httpResponse=new Response(200);
        $httpResponse->getBody()->write($this->viewBuilder->build('@article/index',
            ['articles' => $appResponse->getArticles(),
            'pagination'=>$appResponse->getPagination()]));
        return $httpResponse;
    }

    public function show(RequestInterface $request): ResponseInterface{
        $appRequest=new ShowArticleRequest($request->getAttribute('id'));
        $appService=$this->container->get(ShowArticleApplication::class);
        $appResponse=$appService($appRequest);
        
        if($appResponse->hasErrors()){
            return $this->redirectTo(self::INDEX);
        }
        return $this->buildResponse($this->viewBuilder->build('@article/article',['article'=>$appResponse->getArticle()]));
    }
}
