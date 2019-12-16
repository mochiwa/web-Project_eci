<?php

namespace App\Article\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of IArticleController
 *
 * @author mochiwa
 */
interface IArticleController {
    function index(RequestInterface $request): ResponseInterface;
    function show(RequestInterface $request): ResponseInterface;
}
