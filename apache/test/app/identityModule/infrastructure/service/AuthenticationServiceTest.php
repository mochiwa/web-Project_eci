<?php

use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\User;
use App\Identity\Model\User\Username;
use Framework\Session\SessionManager;
use PHPUnit\Framework\TestCase;

/**
 * Description of AuthenticationServiceTest
 *
 * @author mochiwa
 */
class AuthenticationServiceTest extends TestCase{
    private $userRepository;
    private $authentication;
    private $sessionManager;
    
    
    protected function setUp() {
        $this->userRepository=$this->createMock(IUserRepository::class);
        $this->sessionManager=$this->createMock(SessionManager::class);
        $this->authentication=new AuthenticationService($this->userRepository,$this->sessionManager);
        
        
    }
    function test_authentication_shouldThrowAuthenticationException_whenUsernameNotFoundInRepository()
    {
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willThrowException(new EntityNotFoundException());
        
        $this->expectException(AuthenticationException::class);
        $this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
    }
    
    function test_authentication_shouldThrowAuthenticationException_whenClearPasswordNotMathWithUserPassword()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(false);
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        
        $this->expectException(AuthenticationException::class);
        $this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
    }
    
    function test_authentication_shouldAppendUserToTheSession_whenUserIsAuthenticated()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        $this->sessionManager->expects($this->once())->method('set')->with(SessionManager::CURRENT_USER_KEY,$user);
        
        $this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
    }
    
    function test_isUserConnected_shouldReturnTrue_whenAUserIsAlreadyConnectedInSession()
    {
        $this->sessionManager->expects($this->once())->method('get')->willReturn(Test\App\Identity\Helper\UserBuilder::of()->build());
        
        $this->assertTrue($this->authentication->isUserConnected());
    }
    
    function test_authentication_shouldThrowAuthenticationException_WhenUserIsAlreadyConnectedInSession()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(Test\App\Identity\Helper\UserBuilder::of()->build());
        
        $this->expectException(AuthenticationException::class);
        $this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
    }
    
    
    function test_authentication_shouldReturnTheUser_whenAuthenticationSuccess()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(null);
        
        $userAuthenticated=$this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
        $this->assertSame($user, $userAuthenticated);
    }
    
    
    
    function test_logout_shouldRemoveTheUserInSession_whenUserIsConnected()
    {
        $this->sessionManager->expects($this->once())->method('get')->willReturn(Test\App\Identity\Helper\UserBuilder::of()->build());
        $this->sessionManager->expects($this->once())->method('delete')->with(SessionManager::CURRENT_USER_KEY);
        
        $this->authentication->logout();
    }
}
