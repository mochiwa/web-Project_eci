<?php

namespace App\Identity\Application;

use App\Identity\Application\Request\SignInRequest;
use App\Identity\Application\Response\SignInResponse;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\Username;

/**
 * Description of SignInApplication
 *
 * @author mochiwa
 */
class SignInApplication {
    /**
     * the user repository
     * @var AuthenticationService 
     */
    private $authenticationService;
    
    private $errors;
    private $userView;
    

    public function __construct(AuthenticationService $authenticationService) {
        $this->authenticationService = $authenticationService;
    }
    
    
    public function __invoke(SignInRequest $request) : SignInResponse {
        try{
            $username= Username::of($request->getUsername());
            $password= Password::secure($request->getPassword());
            
            $this->authenticationService->authentication($username, $password);
        } catch (\InvalidArgumentException $input){
            $this->errors=['authentication'=>'Username or password incorrect'];
            $this->userView= Response\UserView::fromArray($request->toArray());
        } catch (AuthenticationException $authentication) {
            $this->errors=['authentication'=>'Username or password incorrect'];
            $this->userView= Response\UserView::fromArray($request->toArray());
        }finally{
            return $this->buildResponse();
        }
    }
    
    
    private function buildResponse(): SignInResponse{
        $response=SignInResponse::of();
        if(!empty($this->errors))
        {
            $response->withErrors($this->errors);
        }
        if(isset($this->userView))
        {
            $response->withUserView($this->userView);
        }
        return $response;
    }

    
    
}
