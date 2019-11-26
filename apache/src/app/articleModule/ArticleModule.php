<?php
namespace App\Article;

use App\Article\Controller\AdminArticleController;
use Framework\Module\AbstractModule;
use Framework\Renderer\IViewBuilder;
use Framework\Router\IRouter;

/**
 * Description of ArticleModule
 *
 * @author mochiwa
 */
class ArticleModule extends AbstractModule {
    const DEFINITION = __DIR__ . '/config.php';
    
    public function __construct(IRouter $router, IViewBuilder $viewBuilder) {
        $router->map('GET', '/parking', AdminArticleController::class, 'parking.home');
        $router->map('GET', '/parking/admin/create', AdminArticleController::class, 'parking.create');
        $router->map('POST', '/parking/admin/create', AdminArticleController::class, 'parking.create-process');
        
        $viewBuilder->addPath('article', __DIR__.'/view');
    }
}
