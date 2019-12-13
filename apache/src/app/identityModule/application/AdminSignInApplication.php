<?php

namespace App\Identity\Application;
use App\Identity\Application\AbstractUserApplication;
use App\Identity\Application\Request\SignInRequest;
use App\Identity\Application\Response\UserApplicationResponse;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\Username;


/**
 * Description of AdminSignInApplication
 *
 * @author mochiwa
 */
class AdminSignInApplication extends AbstractUserApplication{
    private $repository;
    
    /**
     * @var AuthenticationService 
     */
    private $authentication;
    
    
    public function __construct(AuthenticationService $authentication) {
        $this->authentication = $authentication;
    }

    
    function __invoke(SignInRequest $request): UserApplicationResponse
    {
       try{
           $username= Username::of($request->getUsername());
           $password= Password::of($request->getPassword());
           $user=$this->authentication->authentication($username, $password);
           $this->authentication->setConnectedUserInSession($user);
           if(!$user->isAdmin()){
               $this->authentication->logout();
               $this->errors=['authentication'=>'Username or password incorrect'];
           }
       } catch (AuthenticationException $ex) {
            $this->errors=['authentication'=>'Username or password incorrect'];
       }finally{
            return $this->buildResponse();
        }
    }
}
