<?php
namespace App\User;

use App\User\Controller\UserController;
use Framework\Module\AbstractModule;
use Framework\Renderer\IViewBuilder;
use Framework\Router\IRouter;

/**
 * Description of UserModule
 * That module represent everything about user like sign in / sign up edit ....
 *
 * @author mochiwa
 */
class UserModule extends AbstractModule {
    const DEFINITION = __DIR__ . '/config.php';
    
    public function __construct(IRouter $router, IViewBuilder $viewBuilder) {
        $viewBuilder->addPath('user', __DIR__.'/view');
        
        $router->map('GET', '/user', UserController::class, 'user');
        $router->map('GET', '/user/register', UserController::class, 'user.register');
    }
}