<?php

namespace App\Identity\Application;

use App\Identity\Application\Request\LogoutRequest;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;


/**
 * Description of LogoutApplication
 *
 * @author mochiwa
 */
class LogoutApplication extends AbstractUserApplication{
    /**
     *
     * @var AuthenticationService 
     */
    private $authenticationService;
    
    public function __construct(AuthenticationService $authenticationService) {
        $this->authenticationService = $authenticationService;
    }

    
    public function __invoke(LogoutRequest $request): Response\UserApplicationResponse {
        try{
            $this->authenticationService->logout();
        } catch (AuthenticationException $ex) {
            $this->errors=['application'=>'You are not connected , so you cannot logout ...'];
        }
        finally {
            return $this->buildResponse();
        }
    }

}
