<?php

use App\Identity\Application\AdminSignInApplication;
use App\Identity\Application\Request\SignInRequest;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use App\Identity\Model\User\IUserRepository;
use PHPUnit\Framework\TestCase;
use Test\App\Identity\Helper\UserBuilder;

class AdminSignInApplicationTest extends TestCase{
    private $authentication;
    private $repository;
    private $application;
    
    protected function setUp() {
        $this->authentication=$this->createMock(AuthenticationService::class);
        $this->repository=$this->createMock(IUserRepository::class);
        $this->application=new AdminSignInApplication($this->authentication);
    }
    
    function test_invoke_shouldReturnAnErrorResponse_whenAuthenticationFailed() {
        $this->authentication->expects($this->once())->method('authentication')->willThrowException(new AuthenticationException());
        
        $response= call_user_func($this->application,new SignInRequest('username', 'password'));
        $this->assertTrue($response->hasErrors());
    }
    
    function test_invoke_shouldReturnAnErrorResponse_whenUserIsNotAnAdministrator(){
        $user=$this->createMock(App\Identity\Model\User\User::class);
        $this->authentication->expects($this->once())->method('authentication')->willReturn($user);
        $user->expects($this->once())->method('isAdmin')->willReturn(false);
        $response= call_user_func($this->application,new SignInRequest('username', 'password'));
        $this->assertTrue($response->hasErrors());
    }
    function test_invoke_shouldLogoutTheUser_whenUserIsNotAnAdministrator(){
        $user=$this->createMock(App\Identity\Model\User\User::class);
        $this->authentication->expects($this->once())->method('authentication')->willReturn($user);
        $user->expects($this->once())->method('isAdmin')->willReturn(false);
        $this->authentication->expects($this->once())->method('logout');
        $response= call_user_func($this->application,new SignInRequest('username', 'password'));
        
        
    }
    
    function test_invoke_shouldReturnSuccessResponse_whenUserIsAnAdministrator(){
        $user=$this->createMock(App\Identity\Model\User\User::class);
        $this->authentication->expects($this->once())->method('authentication')->willReturn($user);
        $user->expects($this->once())->method('isAdmin')->willReturn(true);
        $response= call_user_func($this->application,new SignInRequest('username', 'password'));
        $this->assertFalse($response->hasErrors());
    }
    function test_invoke_shouldAppendTheAdminInSesion_whenUserIsAnAdministrator(){
        $user=$this->createMock(App\Identity\Model\User\User::class);
        $this->authentication->expects($this->once())->method('authentication')->willReturn($user);
        $user->expects($this->once())->method('isAdmin')->willReturn(true);
        $this->authentication->expects($this->once())->method('setConnectedUserInSession')->with($user);
        $response= call_user_func($this->application,new SignInRequest('username', 'password'));
    }
    

}
