<?php
namespace App\WebPage;

use App\WebPage\Controller\webPageController;
use Framework\Module\AbstractModule;
use Framework\Renderer\IViewBuilder;
use Framework\Router\IRouter;
/**
 * Description of WebPageApp
 *
 * @author mochiwa
 */
class WebPageModule extends AbstractModule {
    
    public function __construct(IRouter $router, IViewBuilder $viewBuilder) {
        $router->map('GET', '/home', webPageController::class,'webPage.home');
        $router->map('GET', '/contact', webPageController::class,'webPage.contact');
        $viewBuilder->addPath('webPage', __DIR__.'/view');
    }
}
