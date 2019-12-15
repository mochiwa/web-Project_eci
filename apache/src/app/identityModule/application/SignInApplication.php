<?php

namespace App\Identity\Application;

use App\Identity\Application\Request\SignInRequest;
use App\Identity\Application\Response\UserApplicationResponse;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\Username;
use InvalidArgumentException;

/**
 * Description of SignInApplication
 *
 * @author mochiwa
 */
class SignInApplication extends AbstractUserApplication{
    /**
     * the user repository
     * @var AuthenticationService 
     */
    private $authenticationService;
    

    public function __construct(AuthenticationService $authenticationService) {
        $this->authenticationService = $authenticationService;
    }
    
    
    public function __invoke(SignInRequest $request) : UserApplicationResponse {
        try{
            $this->userView= Response\UserView::fromArray($request->toArray());
            $username= Username::of($request->getUsername());
            $password= Password::secure($request->getPassword());
            
            $user=$this->authenticationService->authentication($username, $password);
            $this->authenticationService->setConnectedUserInSession($user);
            if($request->storeInCookie()){
                $this->authenticationService->setConnectedUserInCookie($user);
            }
        } catch (InvalidArgumentException $input){
            $this->errors=['authentication'=>'Username or password incorrect'];
        } catch (AuthenticationException $authentication) {
            $this->errors=['authentication'=>'Username or password incorrect'];
        }finally{
            return $this->buildResponse();
        }
    }
}
