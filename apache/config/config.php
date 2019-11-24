<?php
require_once 'RendererFactoryConfig.php';
use App\Controller\UserController;
use Framework\Renderer\IViewBuilder;
use Framework\Renderer\ViewBuilder;
use Framework\Router\Router;
return [
    IViewBuilder::class => function(){return new ViewBuilder();},
    Router::class => function(){return new Router();},
            
    RendererFactoryConfig::class => new RendererFactoryConfig($di),
            
    UserController::class => function($di){
        return new UserController($di->get(Router::class),$di->get(RendererFactoryConfig::class));
    }        
];
