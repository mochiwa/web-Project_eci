<?php

use Framework\Renderer\IViewBuilder;
use Framework\Renderer\ViewBuilder;
use Framework\Router\IRouter;
use Framework\Router\Router;

return [
    IViewBuilder::class => function(){return new ViewBuilder();},
    IRouter::class => function(){return new Router();},
];
