<?php

use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\User;
use App\Identity\Model\User\Username;
use Framework\Cookie\CookieManager;
use Framework\Cookie\CookieStoreException;
use Framework\Session\SessionManager;
use PHPUnit\Framework\TestCase;
use Test\App\Identity\Helper\UserBuilder;

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
        $this->cookieManager=$this->createMock(CookieManager::class);//new CookieManager(new CookieAdapater());//$this->createMock(CookieManager::class);
        $this->authentication=new AuthenticationService($this->userRepository,$this->sessionManager,$this->cookieManager);
    }
    
    
    
    function test_authentication_shouldThrowAuthenticationException_whenUsernameNotFoundInRepository()
    {
        $this->userRepository->expects($this->once())->method('findUserByUsername')->willThrowException(new EntityNotFoundException());
        
        $this->expectException(AuthenticationException::class);
        $this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
    }
    function test_authentication_shouldThrowAuthenticationException_whenClearPasswordNotMatchWithUserPassword()
    {
        $user=$this->createMock(User::class);
        $this->userRepository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(false);
        
        $this->expectException(AuthenticationException::class);
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
        $this->userRepository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(UserBuilder::of()->build());
        
        $this->expectException(AuthenticationException::class);
        $this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
    }
    
    function test_authentication_shouldReturnTheUser_whenAuthenticationSuccess()
    {
        $user=$this->createMock(User::class);
        $this->userRepository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(null);
        
        $userAuthenticated=$this->authentication->authentication(Username::of('aUsername'), Password::secure('aPassword'));
        $this->assertSame($user, $userAuthenticated);
    }
    
    
    
    function test_logout_shouldThrowAuthenticationException_whenNoUserConnected()
    {
        $this->sessionManager->expects($this->once())->method('get')->willReturn(null);
        $this->expectException(AuthenticationException::class);
        
        $this->authentication->logout();
    }
    function test_logout_shouldRemoveTheCookie_whenConnectedCookieExist()
    {
        $this->cookieManager->expects($this->once())->method('hasCookie')->willReturn(true);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(UserBuilder::of()->build());
        $this->cookieManager->expects($this->once())->method('eraseCookie')->with(AuthenticationService::COOKIE_CONNECTED_USER);
        $this->authentication->logout();
        
    }
    function test_logout_shouldRemoveTheUserInSession_whenUserIsConnected()
    {
        $this->sessionManager->expects($this->once())->method('get')->willReturn(UserBuilder::of()->build());
        $this->sessionManager->expects($this->once())->method('delete')->with(SessionManager::CURRENT_USER_KEY);
        
        $this->authentication->logout();
    }
    
    
    function test_authenticateByCookie_shouldThrowAuthenticationException_whenCookieNotFound()
    {
        $this->cookieManager->expects($this->once())->method('getDecodedValuesFromCookie')->willThrowException(new CookieStoreException());
        $this->expectException(AuthenticationException::class);
        $this->authentication->authenticateByCookie();
    }
    function test_authenticateByCookie_shouldUseCredentialFromCookie_whenUserCookieExist()
    {
        $this->cookieManager->expects($this->once())->method('getDecodedValuesFromCookie')->willReturn(['username'=>'johnDoe','password'=>'aPassword']);
        $user= $this->createMock(User::class);
        $this->userRepository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $user->expects($this->once())->method('isPasswordMatch')->willReturn(true);
        $this->sessionManager->expects($this->once())->method('get')->willReturn(null);
        
        
        $this->authentication->authenticateByCookie();
    }
    
    
    
    
    function test_setConnectedUserInSession_shouldThrowAuthenticationException_whenAnUserIsAlreadyPresentInSession()
    {
        $this->sessionManager->expects($this->once())->method('has')->willReturn(true);
        $this->expectException(AuthenticationException::class);
        
        $this->authentication->setConnectedUserInSession(UserBuilder::of()->build());
    }
    
    function test_setConnectedUserInSession_shouldAppendTheUserInSession()
    {
        $user= UserBuilder::of()->build();
        $this->sessionManager->expects($this->once())->method('has')->willReturn(false);
        $this->sessionManager->expects($this->once())->method('set')->with(AuthenticationService::SESSION_CONNECTED_USER,$user);
        
        $this->authentication->setConnectedUserInSession($user);
    }
    
    function test_setConnectedUserInCookie_shouldThrowAuthenticationException_whenAnUserIsAlreadyPresentInSession()
    {
        $this->cookieManager=$this->createMock(CookieManager::class);
        $this->cookieManager->expects($this->once())->method('hasCookie')->willReturn(true);
        $this->authentication=new AuthenticationService($this->userRepository, $this->sessionManager, $this->cookieManager);
        $this->expectException(AuthenticationException::class);
        
        $this->authentication->setConnectedUserInCookie(UserBuilder::of()->build());
    }
    
   
    
}
