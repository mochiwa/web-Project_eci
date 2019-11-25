<?php

use App\User\UserModule;
use App\User\View\ViewFactory;
use Framework\Router\IRouter;
return [
    ViewFactory::class => function($di){return new ViewFactory($di);}, 
           
    UserModule::class => function($di){
             return new UserModule($di->get(IRouter::class),$di->get(ViewFactory::class)->__invoke());
    } 
];