<?php
require '../vendor/autoload.php';
echo "hello";



$router=new Framework\Router\Router();
$router->registerGet('/test/[i:id]', function(){echo 'testss';}, 'test');
$route=$router->match(new \GuzzleHttp\Psr7\Request('GET','/test/5',['teste']));

var_dump($route);