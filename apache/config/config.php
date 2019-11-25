<?php

use Framework\DependencyInjection\IContainer;
use Framework\Middleware\IMiddlewareDispatcher;
use Framework\Middleware\MiddlewareDispatcher;
use Framework\Renderer\IViewBuilder;
use Framework\Renderer\ViewBuilder;
use Framework\Router\IRouter;
use Framework\Router\Router;

return [
    IContainer::class => function($di){return $di;},
    IViewBuilder::class => function(){return ViewBuilder::buildWithLayout('template', dirname(__DIR__).'/template', 'layout');},
    IRouter::class => function(){return new Router();},
    IMiddlewareDispatcher::class => function(){return new MiddlewareDispatcher();}
];
