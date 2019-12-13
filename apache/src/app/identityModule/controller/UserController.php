<?php

namespace App\Identity\Controller;

use App\Identity\Application\LogoutApplication;
use App\Identity\Application\RegisterUserApplication;
use App\Identity\Application\Request\LogoutRequest;
use App\Identity\Application\Request\NewActivationRequest;
use App\Identity\Application\Request\ProcessActivationRequest;
use App\Identity\Application\Request\RegisterUserRequest;
use App\Identity\Application\Request\SignInRequest;
use App\Identity\Application\SignInApplication;
use App\Identity\Application\UserActivationApplication;
use Framework\Connection\AtomicRemoteOperation;
use Framework\Controller\AbstractController;
use Framework\DependencyInjection\IContainer;
use Framework\Renderer\IViewBuilder;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of UserController
 *
 * @author mochiwa
 */
class UserController extends AbstractController implements IUserController{
    const INDEX="home";
    
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
        $this->viewBuilder=$container->get(IViewBuilder::class);
        $this->atomicOperator=$container->get(AtomicRemoteOperation::class);
    }
    
    /**
     * {@inheritdoc}
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request) : ResponseInterface{
        $action=$request->getAttribute('action');
        if(method_exists(IUserController::class, $action) && is_callable([$this,$action])){
                return call_user_func([$this,$action],$request);
        }
        return $this->redirectTo(self::INDEX);
    }
 
    /**
     * If the request is a GET request then return the user register view
     * else return the registrationProcess
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function register(RequestInterface $request): ResponseInterface
    {
        if(!$this->isPostRequest($request)){
            return $this->buildResponse($this->viewBuilder->build('@user/userRegister'));
        }
        return $this->registrationProcess($request);
    }
    /**
     * Launch the registration process, if an error occurs
     * then return a response with errors and userView to the user register view
     * else return the activation result. 
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    private function registrationProcess(RequestInterface $request): ResponseInterface
    {
        $appRequest=RegisterUserRequest::fromPost($request->getParsedBody());
        $appService=$this->container->get(RegisterUserApplication::class);
        $appResponse= call_user_func_array($this->atomicOperator,[$appService,[$appRequest]]);
        
        if($appResponse->hasErrors())
        {
            $body=$this->viewBuilder->build('@user/userRegister',[
                'errors'=>$appResponse->getErrors(),
                'user'=>$appResponse->getUserView()
            ]);
            return $this->buildResponse($body, 400);
        }
        return $this->requestActivation($appResponse->getUserView()->getUserName());
    }
    
    /**
     * Launch the process to request an activation method for the user account
     * validation. return to userRegister view , if error occur then append errors
     * else a flash message with instruction to process the validation will
     * be in session
     * 
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    private function requestActivation(string $username) : ResponseInterface
    {
        $appRequest= NewActivationRequest::of($username);
        $appService=$this->container->get(UserActivationApplication::class);
        $appResponse=$appService($appRequest);
        
        if($appResponse->hasErrors())
        {
            $body=$this->viewBuilder->build('@user/userRegister',['errors'=>$appResponse->getErrors()]);
            return $this->buildResponse($body, 400);
        }
        return $this->buildResponse($this->viewBuilder->build('@user/userRegister'), 200);
    }
    
    
    /**
     * Launch the activation process ,
     * If an result then redirect to the login page with error,
     * else try to connect user
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function activation(RequestInterface $request) : ResponseInterface
    {
        $appRequest= ProcessActivationRequest::of($request->getAttribute('id'));
        $appService=$this->container->get(UserActivationApplication::class);
        $appResponse= call_user_func_array($this->atomicOperator, [$appService,[$appRequest]]);
        
        if($appResponse->hasErrors())
        {
            $body=$this->viewBuilder->build('@user/login',['errors'=>$appResponse->getErrors()]);
            return $this->buildResponse($body, 400);
        }
        return $this->redirectTo('/user/signIn');
    }
    
    /**
     * Manage the sign in process
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function signIn(RequestInterface $request) : ResponseInterface
    {
        if(!$this->isPostRequest($request))
        {
            return $this->buildResponse($this->viewBuilder->build('@user/login'), 200);
        }
        
        $appRequest= SignInRequest::fromPost($request->getParsedBody());
        $appService=$this->container->get(SignInApplication::class);
        $appResponse= $appService($appRequest);
        if($appResponse->hasErrors())
        {
            $body=$this->viewBuilder->build('@user/login',['errors'=>$appResponse->getErrors()]);
            return $this->buildResponse($body, 400);
        }
        return $this->redirectTo(self::INDEX, 200);
    }
    
    
    public function logout(RequestInterface $request) : ResponseInterface
    {
        $appRequest= LogoutRequest::of();
        $appService=$this->container->get(LogoutApplication::class);
        $appResponse= $appService($appRequest);
        return $this->redirectTo(self::INDEX, 200);
    }
}
