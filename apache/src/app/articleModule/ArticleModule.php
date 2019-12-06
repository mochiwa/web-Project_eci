<?php
namespace App\Article;

use App\Article\Controller\AdminArticleController;
use App\Article\Controller\ArticleController;
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
        $router->map('GET', '/parking', ArticleController::class, 'parking.home');
        
       
        $router->map('GET', '/parking/admin', AdminArticleController::class, 'parking.admin.index');
        $router->map('GET', '/parking/admin/[a:action]-page-[a:page]?', AdminArticleController::class, 'parking.admin.page');
        $router->map('GET|POST', '/parking/admin/[a:action]', AdminArticleController::class, 'parking.admin');
        $router->map('GET|POST', '/parking/admin/[a:action]-[a:id]', AdminArticleController::class, 'parking.admin.article');
        
        $viewBuilder->addPath('article', __DIR__.'/view');
    }
}
