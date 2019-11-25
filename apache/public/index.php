<?php

use App\Application;
use App\User\UserModule;
use Framework\DependencyInjection\Container;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;
require '../vendor/autoload.php';

$container=new Container();
$container->appendDefinition(require_once(dirname(__DIR__).'/config/config.php'));

$app = new Application($container);

$app->addModule(UserModule::class);
$app->addModule(\App\Error\ErrorModule::class);

$response=$app->run(ServerRequest::fromGlobals());
send($response);