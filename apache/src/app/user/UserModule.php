<?php
namespace App\User;
use Framework\Renderer\IViewBuilder;
use Framework\Router\Router;

/**
 * Description of UserModule
 * That module represent everything about user like sign in / sign up edit ....
 *
 * @author mochiwa
 */
class UserModule extends \Framework\Module\AbstractModule {
    const DEFINITION = __DIR__ . '/config.php';
    
    public function __construct(Router $router, IViewBuilder $viewBuilder) {
        $viewBuilder->addPath('user', __DIR__.'/view');
        
        $router->map('GET', '/user', Controller\UserController::class, 'user');
        $router->map('GET', '/user/register', Controller\UserController::class, 'user.register');
    }
}
