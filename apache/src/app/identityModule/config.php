<?php

namespace App\Identity;

use App\Identity\Application\RegisterUserApplication;
use App\Identity\Infrastructure\Persistance\InMemoryUserRepository;
use App\Identity\Infrastructure\Validation\UserRegisterValidation;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Service\UserProviderService;

return [
    IUserRepository::class => function(){return new InMemoryUserRepository();},
    
    
    RegisterUserApplication::class => function($di){
        return new RegisterUserApplication($di->get(UserRegisterValidation::class),$di->get(UserProviderService::class));}
];
