<?php
namespace App\Article\Controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of ArticleController
 *
 * @author mochiwa
 */
class ArticleController {
    
    public function __invoke(RequestInterface $request) {
        return $this->index();
    }
    
    public function index() : ResponseInterface
    {
        return new Response();
    }
}
