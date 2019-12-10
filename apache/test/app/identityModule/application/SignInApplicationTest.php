<?php

use App\Identity\Application\Request\SignInRequest;
use App\Identity\Application\SignInApplication;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use PHPUnit\Framework\TestCase;

/**
 * Description of SignInApplicationTest
 *
 * @author mochiwa
 */
class SignInApplicationTest extends TestCase{
    private $application;
    private $authenticationService;
    
    private $request;
    protected function setUp() {
        $this->authenticationService=$this->createMock(AuthenticationService::class);
        $this->application=new SignInApplication($this->authenticationService);
        
        
        $this->request=new SignInRequest('aUsername','aPassword');
    }

    function test_invoke_shouldReturnResponseWithError_whenErrorOccursDuringTheAuthenticationProcess()
    {
       $this->authenticationService->expects($this->once())->method('authentication')->willThrowException(new AuthenticationException());
       $response= call_user_func($this->application,$this->request);
       $this->assertEquals('Username or password incorrect', $response->getErrors()['authentication']);
       
    }
    
    
}
