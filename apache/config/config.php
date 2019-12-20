<?php

use Framework\Acl\ACL;
use Framework\Cookie\CookieManager;
use Framework\Cookie\PhpCookieStore;
use Framework\DependencyInjection\IContainer;
use Framework\Middleware\ACLMiddleware;
use Framework\Middleware\IMiddlewareDispatcher;
use Framework\Middleware\MiddlewareDispatcher;
use Framework\Paginator\PaginationTwigExtension;
use Framework\Renderer\IViewBuilder;
use Framework\Renderer\TwigFactory;
use Framework\Router\IRouter;
use Framework\Router\Router;
use Framework\Router\RouterTwigExtension;
use Framework\Session\PhpSession;
use Framework\Session\SessionManager;
use Framework\Session\SessionTwigExtension;
use Twig\Loader\FilesystemLoader;

return [
    IContainer::class => function($di){return $di;},
    IRouter::class => function(){return new Router();},
    IMiddlewareDispatcher::class => function(){return new MiddlewareDispatcher();},
    
    SessionManager::class => function(){return new SessionManager(new PhpSession());},
    CookieManager::class => function(){return new CookieManager(new PhpCookieStore());},
            
    'twig.extension' => [
       RouterTwigExtension::class,SessionTwigExtension::class
    ],
            
            
    TwigFactory::class => function($di){return new TwigFactory($di,new FilesystemLoader(dirname(__DIR__).'/template'));},
    IViewBuilder::class => function ($di){return $di->get(TwigFactory::class)($di->get('twig.extension'));},        

    ACLMiddleware::class=>function($di){ return new ACLMiddleware(
            $di->get(SessionManager::class),
            ACL::fromArray(include_once 'acl.php')
        );}
];
