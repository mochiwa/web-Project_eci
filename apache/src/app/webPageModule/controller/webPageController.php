<?php
namespace App\WebPage\Controller;

use Framework\Controller\AbstractController;
use Framework\DependencyInjection\IContainer;
use Framework\Renderer\IViewBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
/**
 * Description of webPageController
 *
 * @author mochiwa
 */
class webPageController extends AbstractController implements IWebPageController{
    const INDEX="home";
    /**
     * @var IViewBuilder 
     */
    private $viewBuilder;
    
    function __construct(IContainer $container) {
        parent::__construct($container);
        $this->viewBuilder=$container->get(IViewBuilder::class);
    }
    
    public function __invoke(RequestInterface $request) : ResponseInterface{
        $action = substr($request->getUri()->getPath(), 1);
        
        if(method_exists(IWebPageController::class, $action) && is_callable([$this,$action])){
                return call_user_func([$this,$action],$request);
        }
        return $this->redirectTo(self::INDEX);
    }

    public function contact(RequestInterface $request): ResponseInterface {
        return $this->buildResponse($this->viewBuilder->build('@webPage/contact'), 200);
    }

    public function home(RequestInterface $request): ResponseInterface {
        return $this->buildResponse($this->viewBuilder->build('@webPage/home'), 200);
    }

    protected function index(RequestInterface $request): ResponseInterface {
        return $this->home($request);
    }

}
