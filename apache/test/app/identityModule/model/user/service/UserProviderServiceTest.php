<?php

use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Service\Request\UserProviderRequest;
use App\Identity\Model\User\Service\UserProviderException;
use App\Identity\Model\User\Service\UserProviderService;
use App\Identity\Model\User\UserId;
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
    
    function test_provide_shouldThrowUserProviderException_whenEmailIsAlreadyUsed()
    {
        $request=UserProviderRequest::of('myEmail@email.com');
        $this->userRepository->expects($this->once())->method('isEmailFree')->willReturn(false);
        $exception=false;
        try{
            $this->provider->provide($request);
        } catch (UserProviderException $ex) {
            $this->assertEquals('The email myEmail@email.com is already used', $ex->getMessage());
            $exception=true;
        }
        $this->assertTrue($exception);
    }
    
    function test_provide_shouldThrowUserProviderException_whenUsernameIsAlreadyUsed()
    {
        $request=UserProviderRequest::of('myEmail@email.com','aUsername');
        $this->userRepository->expects($this->once())->method('isEmailFree')->willReturn(true);
        $this->userRepository->expects($this->once())->method('isUsernameFree')->willReturn(false);
        
        $exception=false;
        try{
            $this->provider->provide($request);
        } catch (UserProviderException $ex) {
            $this->assertEquals('The username aUsername is already used', $ex->getMessage());
            $exception=true;
        }
        $this->assertTrue($exception);
    }
    
    function test_provide_shouldThrowUserProviderException_whenPasswordIsNotSecure()
    {
        $request=UserProviderRequest::of('myEmail@email.com','aUsername','');
        $this->userRepository->expects($this->once())->method('isEmailFree')->willReturn(true);
        $this->userRepository->expects($this->once())->method('isUsernameFree')->willReturn(true);
        
        $exception=false;
        try{
            $this->provider->provide($request);
        } catch (UserProviderException $ex) {
            $this->assertEquals('The password is not secure enough', $ex->getMessage());
            $exception=true;
        }
        $this->assertTrue($exception);
    }
    
    function test_provide_shouldReturnAUserWIthAUniqueId_whenNoErrorOccursDuringTheProcess()
    {
        $request=UserProviderRequest::of('myEmail@email.com','aUsername','aPassword');
        $userId=UserId::of('aaa');
        $this->userRepository->expects($this->once())->method('isEmailFree')->willReturn(true);
        $this->userRepository->expects($this->once())->method('isUsernameFree')->willReturn(true);
        $this->userRepository->expects($this->once())->method('nextId')->willReturn($userId);
        $user=$this->provider->provide($request);
        $this->assertEquals('aaa', $user->id()->idToString());
    }
    
    function test_provide_shouldProvideANotActivedUser_whenNoErrorOccursDuringTheProcess()
    {
        $request=UserProviderRequest::of('myEmail@email.com','aUsername','aPassword');
        $this->userRepository->expects($this->once())->method('isEmailFree')->willReturn(true);
        $this->userRepository->expects($this->once())->method('isUsernameFree')->willReturn(true);
        $user=$this->provider->provide($request);
        $this->assertFalse($user->isActived());
    }
    
    function test_provide_shouldAppendTheUserInRepository_whenNoErrorOccure()
    {
        $request=UserProviderRequest::of('myEmail@email.com','aUsername','aPassword');
        $this->userRepository->expects($this->once())->method('isEmailFree')->willReturn(true);
        $this->userRepository->expects($this->once())->method('isUsernameFree')->willReturn(true);
        $this->userRepository->expects($this->once())->method('addUser');
        $user=$this->provider->provide($request);
        
    }
    
    
    
}
