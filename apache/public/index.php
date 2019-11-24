<?php
require '../vendor/autoload.php';

$container=new Framework\DependencyInjection\Container();
$container->appendDefinition(require_once(dirname(__DIR__).'/config/config.php'));

$app = new App\Application($container);
$app->addModule(\App\Controller\UserController::class);
$response=$app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());

\Http\Response\send($response);