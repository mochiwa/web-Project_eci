<?php

use App\Identity\Application\AdminSignInApplication;
use App\Identity\Application\Request\SignInRequest;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use PHPUnit\Framework\TestCase;

class AdminSignInApplicationTest extends TestCase{
    private $authentication;
    private $application;
    
    protected function setUp() {
        $this->authentication=$this->createMock(AuthenticationService::class);
        $this->application=new AdminSignInApplication($this->authentication);
    }
    
    function test_invoke_shouldReturnAnErrorResponse_whenAuthenticationFailed() {
        $this->authentication->expects($this->once())->method('adminAuthentication')->willThrowException(new AuthenticationException());
        
        $response= call_user_func($this->application,new SignInRequest('username', 'password'));
        $this->assertTrue($response->hasErrors());
    }
    

}
