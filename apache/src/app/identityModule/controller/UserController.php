<?php

namespace App\Identity\Controller;

use App\Identity\Application\RegisterUserApplication;
use App\Identity\Application\Request\RegisterUserRequest;
use App\Identity\Application\Request\UserActivationRequest;
use App\Identity\Application\UserActivationApplication;
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
class UserController extends AbstractController{
    const INDEX="/home";
    
    private $viewBuilder;
    
    function __construct(IContainer $container) {
        parent::__construct($container);
        $this->viewBuilder=$container->get(IViewBuilder::class);
    }
    
    /**
     * {@inheritdoc}
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request) : ResponseInterface{
        $action=$request->getAttribute('action');
        
        if(method_exists($this, $action) && is_callable([$this,$action])){
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
    private function register(RequestInterface $request): ResponseInterface
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
        $appResponse=$appService($appRequest);
        
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
        $appRequest=UserActivationRequest::newActivationFor($username);
        $appService=$this->container->get(UserActivationApplication::class);
        $appResponse=$appService($appRequest);
        
        if($appResponse->hasErrors())
        {
            $body=$this->viewBuilder->build('@user/userRegister',['errors'=>$appResponse->getErrors()]);
            return $this->buildResponse($body, 400);
        }
        return $this->buildResponse($this->viewBuilder->build('@user/userRegister'), 200);
    }
    
    
    
    /*
    private function activation(RequestInterface $request): ResponseInterface
    {
        if($request->hasHeader('username'))
        {
            $username=$request->getHeader('username')[0];
            $applicationRequest= UserActivationRequest::newActivationFor($username);
        }
        else
        {
            $userId=$request->getAttribute('id');
            $applicationRequest= UserActivationRequest::of($id);
        }
        $applicationService=$this->container->get(UserActivationApplication::class);
        $applicationResponse=$applicationService($applicationRequest);
        
        if($applicationResponse->hasErrors())
        {
            $response=new Response(400);//todo : redict to login
            $response->getBody()->write($this->viewBuilder->build('@user/login',[
                'errors'=>$applicationResponse->getErrors()]));
            return $response;
        }
        elseif ($applicationResponse->hasActivationLink()) 
        {
            $response=new Response(200);
            $response->getBody()->write($this->viewBuilder->build('@user/userRegister'));
            return $response;
        }
    }*/
}
