<?php

use App\Article\Controller\ArticleController;
use App\Identity\Controller\AdminController;
use App\Identity\Controller\UserController;
use App\WebPage\Controller\webPageController;
use Framework\Acl\AbstractTarget;
use Framework\Acl\ACL;
use Framework\Acl\Role;

return[
    ACL::ROLES_INDEX =>[Role::of('admin', 99),Role::of('user', 1),Role::of('visitor', 0)],
    ACL::RULES_INDEX =>[
        'admin'=>[
            ACL::ALLOW_INDEX=>[
                AbstractTarget::URL('admin')
        
            ],
            ACL::DENY_INDEX=>[
                AbstractTarget::ControllerAction(UserController::class,'register')
            ]
        ],
        'user'=>[
            ACL::ALLOW_INDEX=>[
                AbstractTarget::ControllerAction(UserController::class,'logout'),
            ],
            ACL::DENY_INDEX=>[
                AbstractTarget::ControllerAction(UserController::class,'register'),
                AbstractTarget::ControllerAction(UserController::class,'activation'),
                AbstractTarget::ControllerAction(UserController::class,'signIn'),
            ]
        ],
        'visitor'=>[
            ACL::ALLOW_INDEX=>[
                AbstractTarget::Controller(webPageController::class),
                AbstractTarget::ControllerAction(UserController::class,'register'),
                AbstractTarget::ControllerAction(UserController::class,'signIn'),
                AbstractTarget::ControllerAction(UserController::class,'activation'),
                AbstractTarget::ControllerAction(AdminController::class,'signIn'),
                AbstractTarget::Controller(ArticleController::class)
            ],
            ACL::DENY_INDEX=>[
            ]
        ],
    ]
];