<?php

namespace Framework\Router;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * This class define a route used in a router
 *
 * @author mochiwa
 */
class Route {

    /**
     * @var string The route name as the router know
     */
    private $name;

    /**
     * @var callable The target 
     */
    private $target;

    /**
     *
     * @var array list of parameters to pass through the target
     */
    private $params;

    public function __construct(string $name, callable $target, array $params = []) {
        $this->name = $name;
        $this->target = $target;
        $this->params = $params;
    }

    /**
     * Return the target
     * @return ? the target
     */
    public function target(): callable {
        return $this->target;
    }

    /**
     * Return list of parameters in the URI
     * @return array
     */
    public function params(): array {
        return $this->params;
    }

}
