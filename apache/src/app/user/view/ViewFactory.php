<?php
namespace App\User\View;

use Framework\DependencyInjection\IContainer;

/**
 * Description of ViewFactory
 *
 * @author mochiwa
 */
class ViewFactory {
    private $container;
    public function __construct(IContainer $container) {
        $this->container=$container;
    }
    
    
    public function __invoke() {
        return $this->container->get(\Framework\Renderer\IViewBuilder::class);
    }
    
    
}
