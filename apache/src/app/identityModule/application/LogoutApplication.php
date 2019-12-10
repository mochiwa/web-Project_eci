<?php

namespace App\Identity\Application;

use App\Identity\Application\Request\LogoutRequest;
use App\Identity\Application\Response\LogoutResponse;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;


/**
 * Description of LogoutApplication
 *
 * @author mochiwa
 */
class LogoutApplication {
    /**
     *
     * @var AuthenticationService 
     */
    private $authenticationService;
    
    public function __construct(AuthenticationService $authenticationService) {
        $this->authenticationService = $authenticationService;
    }

    
    public function __invoke(LogoutRequest $request): LogoutResponse {
        try{
            $this->authenticationService->logout();
            return LogoutResponse::of();
        } catch (AuthenticationException $ex) {
            return LogoutResponse::of()->withErrors(['logout'=>"You are not connected , so you cannot logout ..."]);
        }
        
    }

}
