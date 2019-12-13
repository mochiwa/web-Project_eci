<?php

use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\User;
use App\Identity\Model\User\Username;
use Framework\Cookie\CookieManager;
use Framework\Session\SessionManager;
use PHPUnit\Framework\TestCase;
use Test\App\Identity\Helper\UserBuilder;
use Test\Framework\Cookie\CookieAdapater;

/**
 * Description of AuthenticationServiceTest
 *
 * @author mochiwa
 */
class AuthenticationServiceTest extends TestCase{
    private $userRepository;
    private $authentication;
    private $sessionManager;
    private $cookieManager;
    
    
    protected function setUp() {
        $this->userRepository=$this->createMock(IUserRepository::class);
        $this->sessionManager=$this->createMock(SessionManager::class);
        $this->cookieManager=new CookieManager(new CookieAdapater());//$this->createMock(CookieManager::class);
        $this->authentication=new AuthenticationService($this->userRepository,$this->sessionManager,$this->cookieManager);
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
        $this->sessionManager->expects($this->once())->method('get')->willReturn(UserBuilder::of()->build());
        
        $this->assertTrue($this->authentication->isUserConnected());
    }
    
    function test_authentication_shouldThrowAuthenticationException_WhenUserIsAlreadyConnectedInSession()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(UserBuilder::of()->build());
        
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
        $this->sessionManager->expects($this->once())->method('get')->willReturn(UserBuilder::of()->build());
        $this->sessionManager->expects($this->once())->method('delete')->with(SessionManager::CURRENT_USER_KEY);
        
        $this->authentication->logout();
    }
    
    function test_authenticate_shouldBuildAnUserConnectedCookie_whenAuthenticateSucess()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(null);
        
        $userAuthenticated=$this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
        $this->assertTrue($this->cookieManager->hasCookie(AuthenticationService::COOKIE_CONNECTED_USER));
    }
    
    function test_authenticate_shouldBuildAnUserConnectedCookieWithHisUsernameAndId_whenAuthenticateSucess()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(null);
        
        $userAuthenticated=$this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
        $cookie=$this->cookieManager->getCookie(AuthenticationService::COOKIE_CONNECTED_USER);
        
        $cookieValue= json_decode($cookie['value']);
        
        $this->assertTrue(key_exists('username', $cookieValue));
        $this->assertTrue(key_exists('password', $cookieValue));
    }
    function test_authenticate_shouldBuildAnUserConnectedCookieAvailableFor24h_whenAuthenticateSucess()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(null);
        
        $userAuthenticated=$this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
        $cookie=$this->cookieManager->getCookie(AuthenticationService::COOKIE_CONNECTED_USER);
        
        $this->assertEquals(CookieManager::TIME_ONE_DAY,$cookie['expire']);
    }
    
    function test_authenticateByCookie_shouldUseCredentialFromCookie_whenUserCookieExist()
    {
        $this->cookieManager->addCookie(AuthenticationService::COOKIE_CONNECTED_USER,['username'=>'johnDoe','password'=>'aPassword'], CookieManager::TIME_ONE_DAY);
        $user= UserBuilder::of()->setUsername('johnDoe')->setPassword('aPassword')->build();
        $this->userRepository->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn($user);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(null);
        
        $this->assertSame($user, $this->authentication->authenticateByCookie());
    }
    
     function test_logout_shouldRemoveTheCookie_whenUserIsConnected()
    {
        $this->cookieManager->addCookie(AuthenticationService::COOKIE_CONNECTED_USER,['username'=>'johnDoe','password'=>'aPassword'], CookieManager::TIME_ONE_DAY);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(UserBuilder::of()->build());

        $this->authentication->logout();
        $this->assertFalse($this->cookieManager->hasCookie(AuthenticationService::COOKIE_CONNECTED_USER));
    }
    
}
