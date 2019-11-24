<?php
require '../vendor/autoload.php';

$app = new App\Application();
$app->addModule(\App\Controller\UserController::class);
$app->addModule(\App\Controller\ErrorController::class);
$app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());