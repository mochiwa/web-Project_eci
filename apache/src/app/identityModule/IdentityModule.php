<?php

namespace App\Identity;
use App\Identity\Controller\UserController;
use Framework\Module\AbstractModule;
use Framework\Renderer\IViewBuilder;
use Framework\Router\IRouter;


/**
 * Description of IdentityModule
 *
 * @author mochiwa
 */
class IdentityModule extends AbstractModule{
    const DEFINITION = __DIR__ . '/config.php';
    
    public function __construct(IRouter $router, IViewBuilder $viewBuilder) {
        $router->map('GET|POST', '/user/[a:action]', UserController::class,'user');
        $router->map('GET|POST', '/user/[a:action]-[a:id]', UserController::class,'user.selected');
        
        $viewBuilder->addPath('user', __DIR__.'/view');
    }
}
