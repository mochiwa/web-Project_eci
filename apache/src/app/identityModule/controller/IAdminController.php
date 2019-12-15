<?php

namespace App\Identity\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of IAdminController
 *
 * @author mochiwa
 */
interface IAdminController {
    public function signIn(RequestInterface $request): ResponseInterface;
    public function adminPanel(RequestInterface $request): ResponseInterface;
}
