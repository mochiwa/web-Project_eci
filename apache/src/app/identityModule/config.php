<?php

namespace App\Identity;

use App\Identity\Application\RegisterUserApplication;
use App\Identity\Application\UserActivationApplication;
use App\Identity\Infrastructure\Persistance\InMemoryUserRepository;
use App\Identity\Infrastructure\Service\ActivationByLink;
use App\Identity\Infrastructure\Service\PasswordEncryptionService;
use App\Identity\Infrastructure\Service\UserTwigExtension;
use App\Identity\Infrastructure\Validation\UserRegisterValidation;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Service\UserProviderService;
use App\Shared\Infrastructure\InMemoryTransaction;
use Framework\Connection\AtomicRemoteOperation;
use Framework\DependencyInjection\IContainer;
use Framework\Session\SessionManager;


return [
    IUserRepository::class => function(){return new InMemoryUserRepository();},
    
    AtomicRemoteOperation::class => function($di){
        return new AtomicRemoteOperation(new InMemoryTransaction($di->get(IUserRepository::class)));},
    

    RegisterUserApplication::class => function($di){
        return new RegisterUserApplication(
                $di->get(UserRegisterValidation::class),
                $di->get(UserProviderService::class),
                PasswordEncryptionService::of());},
                
    UserActivationApplication::class => function($di){
        return new UserActivationApplication(
                $di->get(IUserRepository::class),
                $di->get(ActivationByLink::class),
                $di->get(SessionManager::class));
    },
            
    'twig.extension' => [IContainer::ADD=>[UserTwigExtension::class]],
];
