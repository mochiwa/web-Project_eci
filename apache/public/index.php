<?php

use App\Application;
use App\Article\ArticleModule;
use App\User\UserModule;
use Framework\DependencyInjection\Container;
use Framework\Html\HtmlTag;
use Framework\Middleware\ErrorMiddleware;
use Framework\Middleware\LastSlashRemoverMiddleware;
use Framework\Middleware\RouteDispatcherMiddleware;
use Framework\Middleware\RouterMiddleware;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;
require '../vendor/autoload.php';

   //$form->addAttribute((new Attribute('enctype'))->addContent('multipart/form-data'));

$errors=[
    'form-login'=>['not valid'],
    'form-password'=>['not valid']
];

$value=[
    'form-login'=>'typed'
];
        
$form = new Framework\Html\Form('./here', 'post');
$form->addInputWithLabel(new \Framework\Html\Input('login'), 'Your login');
$form->addInputWithLabel(new \Framework\Html\Input('password','password'), 'Your password');

$form->setErrors($errors);
$form->fillForm($value);

echo $form->toHtml();
die();

$container=new Container();
$container->appendDefinition(require_once(dirname(__DIR__).'/config/config.php'));


$app = new Application($container);

$app->addModule(UserModule::class)
        ->addModule(ArticleModule::class);
        
$app->pipe(LastSlashRemoverMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(RouteDispatcherMiddleware::class)
    ->pipe(ErrorMiddleware::class);

$response=$app->run(ServerRequest::fromGlobals());
send($response);
