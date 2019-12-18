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
use Framework\Renderer\TwigViewBuilder;
use Framework\Router\IRouter;
use Framework\Router\Router;
use Framework\Router\RouterTwigExtension;
use Framework\Session\PhpSession;
use Framework\Session\SessionManager;
use Framework\Session\SessionTwigExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    IContainer::class => function($di){return $di;},
    IRouter::class => function(){return new Router();},
    IMiddlewareDispatcher::class => function(){return new MiddlewareDispatcher();},
    
    SessionManager::class => function(){return new SessionManager(new PhpSession());},
    CookieManager::class => function(){return new CookieManager(new PhpCookieStore());},
            
    /*IViewBuilder::class => function($di){return ViewBuilder::buildWithLayout('template', dirname(__DIR__).'/template', 'layout')
            ->addGlobal('router', $di->get(IRouter::class))
            ->addGlobal('session', $di->get(SessionManager::class));},*/
                   
    IViewBuilder::class => function ($di){
        $loader=new FilesystemLoader(dirname(__DIR__).'/template');
        $twig=new Environment($loader);
        $twig->addExtension($di->get(RouterTwigExtension::class));
        $twig->addExtension($di->get(SessionTwigExtension::class));
        $twig->addExtension($di->get(PaginationTwigExtension::class));
        
        return new TwigViewBuilder($loader, $twig);
    },
        
    ACLMiddleware::class=>function($di){ return new ACLMiddleware(
            $di->get(SessionManager::class),
            ACL::fromArray(include_once 'acl.php')
        );}
];
