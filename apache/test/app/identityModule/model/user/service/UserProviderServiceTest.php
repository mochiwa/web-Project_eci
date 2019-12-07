<?php

use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Service\Request\UserProviderRequest;
use App\Identity\Model\User\Service\UserCredentialException;
use App\Identity\Model\User\Service\UserProviderService;
use PHPUnit\Framework\TestCase;

/**
 * Description of UserProviderTest
 *
 * @author mochiwa
 */
class UserProviderServiceTest extends TestCase{
    private $provider;
    private $userRepository;
    
    protected function setUp() {
        $this->userRepository=$this->createMock(IUserRepository::class);
        $this->provider=new UserProviderService($this->userRepository);
    }
    
    function test_provide_shouldThrowUserCredentialException_whenEmailIsAlreadyUsed()
    {
        $request=UserProviderRequest::of('myEmail@email.com');
        $this->userRepository->expects($this->once())->method('isEmailFree')->willReturn(false);
        $exception=false;
        try{
            $this->provider->provide($request);
        } catch (UserCredentialException $ex) {
            $this->assertEquals('The email myEmail@email.com is already used', $ex->getMessage());
            $exception=true;
        }
        $this->assertTrue($exception);
    }
    
    function test_provide_shouldThrowUserCredentialException_whenUsernameIsAlreadyUsed()
    {
        $request=UserProviderRequest::of('myEmail@email.com','aUsername');
        $this->userRepository->expects($this->once())->method('isEmailFree')->willReturn(true);
        $this->userRepository->expects($this->once())->method('isUsernameFree')->willReturn(false);
        
        $exception=false;
        try{
            $this->provider->provide($request);
        } catch (UserCredentialException $ex) {
            $this->assertEquals('The username aUsername is already used', $ex->getMessage());
            $exception=true;
        }
        $this->assertTrue($exception);
    }
    
}
