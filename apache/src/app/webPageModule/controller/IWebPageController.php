<?php
namespace App\WebPage\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of IWebPageController
 *
 * @author mochiwa
 */
interface IWebPageController {
    
    public function home(RequestInterface $request) : ResponseInterface;
    public function contact(RequestInterface $request) : ResponseInterface;
    
}
