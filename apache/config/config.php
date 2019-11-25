<?php
require_once 'RendererFactoryConfig.php';

use App\User\UserModule;
use Framework\Renderer\IViewBuilder;
use Framework\Renderer\ViewBuilder;
use Framework\Router\Router;
return [
    IViewBuilder::class => function(){return new ViewBuilder();},
    Router::class => function(){return new Router();},
            
    RendererFactoryConfig::class => new RendererFactoryConfig($di),
            
    UserModule::class => function($di){
        return new UserModule($di->get(Router::class),$di->get(RendererFactoryConfig::class));
    }        
];
