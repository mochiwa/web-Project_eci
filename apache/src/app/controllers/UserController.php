<?php
namespace \App\Controller;

/**
 * Description of UserControoler
 *
 * @author mochiwa
 */
class UserController {
    public function __construct(Framework\Router\Router $router) {
        $this->router=$router;
    }
}
