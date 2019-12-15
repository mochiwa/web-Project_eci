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
    
    
    function test_invoke_shouldAppendTheUserInSeesion_whenTheAuthenticationSuccess()
    {
        $user=$this->createMock(App\Identity\Model\User\User::class);
        $this->authenticationService->expects($this->once())->method('authentication')->willReturn($user);
        $this->authenticationService->expects($this->once())->method('setConnectedUserInSession')->with($user);
        $response= call_user_func($this->application,$this->request);
    }
    
    function test_invoke_shouldAppendTheUserIntoCookie_whenRequestAskIt()
    {
        $user=$this->createMock(App\Identity\Model\User\User::class);
        $this->authenticationService->expects($this->once())->method('authentication')->willReturn($user);
        $this->authenticationService->expects($this->once())->method('setConnectedUserInSession')->with($user);
        $this->authenticationService->expects($this->once())->method('setConnectedUserInCookie')->with($user);
        $response= call_user_func($this->application,$this->request);
    }
    function test_invoke_shouldNotAppendTheUserIntoCookie_whenRequestDoesNotAskIt()
    {
        $user=$this->createMock(App\Identity\Model\User\User::class);
        $this->authenticationService->expects($this->once())->method('authentication')->willReturn($user);
        $this->authenticationService->expects($this->once())->method('setConnectedUserInSession')->with($user);
        $this->authenticationService->expects($this->never())->method('setConnectedUserInCookie')->with($user);
        $response= call_user_func($this->application,new SignInRequest('username','pass',false));
    }
    
}
