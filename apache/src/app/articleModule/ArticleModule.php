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
    
    public function __construct(\Framework\DependencyInjection\IContainer $di) {
        
        $router=$di->get(IRouter::class);
        $viewBuilder=$di->get(IViewBuilder::class);
        
        
        $router->map('GET', '/parking/[a:action]?', ArticleController::class, 'article');
        $router->map('GET|POST', '/parking/[a:action]-[a:id]', ArticleController::class, 'article.selected');
        $router->map('GET', '/parking/[a:action]-page-[a:page]?', ArticleController::class, 'parking.page');
        
       
        $router->map('GET', '/admin/parking', AdminArticleController::class, 'parking.admin.index');
        $router->map('GET', '/admin/parking/[a:action]-page-[a:page]?', AdminArticleController::class, 'parking.admin.page');
        $router->map('GET|POST', '/admin/parking/[a:action]', AdminArticleController::class, 'parking.admin');
        $router->map('GET|POST', '/admin/parking/[a:action]-[a:id]', AdminArticleController::class, 'parking.admin.article');
        
       /* $router->map('GET', '/parking/admin', AdminArticleController::class, 'parking.admin.index');
        $router->map('GET', '/parking/admin/[a:action]-page-[a:page]?', AdminArticleController::class, 'parking.admin.page');
        $router->map('GET|POST', '/parking/admin/[a:action]', AdminArticleController::class, 'parking.admin');
        $router->map('GET|POST', '/parking/admin/[a:action]-[a:id]', AdminArticleController::class, 'parking.admin.article');*/
        
        $viewBuilder->addPath('article', __DIR__.'/view');
        $viewBuilder->addPath('article/admin', __DIR__.'/view/admin');
    }
}
