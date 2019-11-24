<?php

use Psr\Container\ContainerInterface;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RendererFactoryConfig
 *
 * @author mochiwa
 */
class RendererFactoryConfig {
    
    public function __invoke(ContainerInterface $container) {
        $viewBuilder=$container->get(\Framework\Renderer\IViewBuilder::class);
        $viewBuilder->addPath('template', dirname(__DIR__).'/template');
        $viewBuilder->addGlobal('router',$container->get(Framework\Router\Router::class));
        return $viewBuilder;
    }
}
