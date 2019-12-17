<?php

use App\Application;
use App\Article\ArticleModule;
use App\Identity\IdentityModule;
use App\WebPage\WebPageModule;
use Framework\DependencyInjection\Container;
use Framework\Middleware\ACLMiddleware;
use Framework\Middleware\ErrorMiddleware;
use Framework\Middleware\LastSlashRemoverMiddleware;
use Framework\Middleware\RouteDispatcherMiddleware;
use Framework\Middleware\RouterMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;
require '../vendor/autoload.php';




$container=new Container();
$container->appendDefinition(require_once(dirname(__DIR__).'/config/config.php'));


$app = new Application($container);

$app->addModule(WebPageModule::class)
    ->addModule(ArticleModule::class)
    ->addModule(IdentityModule::class);
        
$app->pipe(LastSlashRemoverMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(ACLMiddleware::class)
    ->pipe(RouteDispatcherMiddleware::class)
    ->pipe(ErrorMiddleware::class);

$response=$app->run(ServerRequest::fromGlobals());
send($response);
