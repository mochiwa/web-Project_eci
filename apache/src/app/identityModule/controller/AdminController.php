<?php

namespace App\Identity\Controller;

use App\Identity\Application\Request\SignInRequest;
use App\Identity\Application\SignInApplication;
use App\Identity\Controller\IAdminController;
use Framework\Connection\AtomicRemoteOperation;
use Framework\Controller\AbstractController;
use Framework\DependencyInjection\IContainer;
use Framework\Renderer\IViewBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * This class is response to manage admin actions into the User and identity context
 * @author mochiwa
 */
class AdminController extends AbstractController implements IAdminController {
    const INDEX = "/admin/user/adminPanel";
    /**
     * @var IViewBuilder 
     */
    private $viewBuilder;
    /**
     * @var AtomicRemoteOperation 
     */
    private $atomicOperator;
    function __construct(IContainer $container) {
        parent::__construct($container);
        $this->viewBuilder = $container->get(IViewBuilder::class);
        $this->atomicOperator = $container->get(AtomicRemoteOperation::class);
    }

    /**
     * {@inheritdoc}
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request): ResponseInterface {
        $action = $request->getAttribute('action');
        if (method_exists(IAdminController::class, $action) && is_callable([$this, $action])) {
            return call_user_func([$this, $action], $request);
        }
        return $this->redirectTo(self::INDEX);
    }

    /**
     * Manage de sign in process for the administrator
     * @param \App\Identity\Controller\RequestInterface $request
     * @return \App\Identity\Controller\ResponseInterface
     */
    public function signIn(RequestInterface $request): ResponseInterface {
        if (!$this->isPostRequest($request)) {
            return $this->buildResponse($this->viewBuilder->build('@user/admin/signIn'), 200);
        }
        
        $appRequest= SignInRequest::fromPost($request->getParsedBody());
        $appService=$this->container->get(\App\Identity\Application\AdminSignInApplication::class);
        $appResponse= $appService($appRequest);
        if($appResponse->hasErrors())
        {
            $body=$this->viewBuilder->build('@user/admin/signIn',['errors'=>$appResponse->getErrors()]);
            return $this->buildResponse($body, 400);
        }
        return $this->redirectTo(self::INDEX, 200);
    }
    
    
    public function adminPanel(RequestInterface $request): ResponseInterface {
        return $this->buildResponse($this->viewBuilder->build('@user/admin/controlPanel'));
    }

    protected function index(RequestInterface $request): ResponseInterface {
        return $this->adminPanel($request);
    }

}
