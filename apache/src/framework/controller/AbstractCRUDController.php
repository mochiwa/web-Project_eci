<?php

use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;
use Swoole\Http\Response;

namespace Framework\Controller;

/**
 * Description of AbstractCRUDController
 *
 * @author mochiwa
 */
abstract class AbstractCRUDController extends AbstractController{
    
    protected abstract function create(RequestInterface $request) : Response;
    protected abstract function read(RequestInterface $request) :Response ;
    protected abstract function udpate(RequestInterface $request): Response;
    protected abstract function delete(RequestInterface $request) : Response;
}
