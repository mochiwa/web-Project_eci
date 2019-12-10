<?php

use App\Identity\Application\Request\NewActivationRequest;
use App\Identity\Application\Request\ProcessActivationRequest;
use App\Identity\Application\UserActivationApplication;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserActivation;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\User;
use App\Identity\Model\User\UserActivation;
use App\Identity\Model\User\UserActivationException;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use PHPUnit\Framework\TestCase;
use Test\App\Identity\Helper\UserBuilder;

/**
 * Description of UserActivationApplication
 *
 * @author mochiwa
 */
class UserActivationApplicationTest extends TestCase{
    private $application;
    private $repository;
    private $activation;
    private $session;
    
    private $processRequest;
    private $newActivationFor;
    
    function setUp() {
        $this->repository=$this->createMock(IUserRepository::class);
        $this->activation=$this->createMock(IUserActivation::class);
        $this->session=$this->createMock(SessionManager::class);
        $this->application=new UserActivationApplication($this->repository,$this->activation,$this->session);
        
        
        $this->processRequest= ProcessActivationRequest::of('anId');
        $this->newActivationFor= NewActivationRequest::of('aUsername');
    }
    
    
   
    
    function test_invoke_shouldTryToFindUserByItsId_whenRequestIsProcessActivation()
    {
        $this->repository->expects($this->once())->method('findUserById');
        $response= call_user_func($this->application,$this->processRequest);
    }
    
    function test_invoke_shouldReturnResponseWithEntityNotFoundError_whenIdNotFoundInRepository()
    {
        $this->repository->expects($this->once())
            ->method('findUserById')
            ->willThrowException(new EntityNotFoundException());
        
        $response= call_user_func($this->application,$this->processRequest);
        
        $this->assertTrue($response->hasErrors());
        $this->assertEquals('Your account was not found',$response->getErrors()['repository']);
    }
    
    function test_invoke_shouldReturnResponseWithUserActivationException_whenUserIsAlreadyValidate()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())
            ->method('active')
            ->willThrowException(new UserActivationException());
        
        
        $this->repository->expects($this->once())
            ->method('findUserById')
            ->willReturn($user);
        
        $response= call_user_func($this->application,$this->processRequest);
        $this->assertTrue($response->hasErrors());
        $this->assertEquals('Your account is already actived',$response->getErrors()['activation']);
    }
    
    function test_invoke_shouldUpdateUser_whenUserWasValidate()
    {
        $user= UserBuilder::of()->setId('aaa')->setActivation(UserActivation::newActivation())->build();
        $this->repository->expects($this->once())->method('findUserById')->willReturn($user);
        $this->repository->expects($this->once())->method('updateUser')->with($user);
        
        $response= call_user_func($this->application,$this->processRequest);
        
        $this->assertFalse($response->hasErrors());
        $this->assertTrue($user->isActived());
    }
    
    function test_invoke_shouldReturnUserView_whenNoErrorOccur()
    {
        $user= UserBuilder::of()->setId('aaa')->setActivation(UserActivation::newActivation())->build();
        $this->repository->expects($this->once())->method('findUserById')->willReturn($user);
        $this->repository->expects($this->once())->method('updateUser')->with($user);
        
        $response= call_user_func($this->application,$this->processRequest);
        
        $this->assertFalse($response->hasErrors());
        $this->assertNotNull($response->getUserView());
    }
    
    function test_invoke_shouldAppendAsuccessFlashMessageToSession_whenTheActivationProcessIsSuccess()
    {
        $user= UserBuilder::of()->setId('aaa')->setActivation(UserActivation::newActivation())->build();
        $this->repository->expects($this->once())->method('findUserById')->willReturn($user);
        $this->repository->expects($this->once())->method('updateUser')->with($user);
        $this->session->expects($this->once())->method('setFlash')->with(FlashMessage::success('Your account is actived, you can now sign in !'));
        
        $response= call_user_func($this->application,$this->processRequest);
        
    }
    
    
    
    function test_invoke_shouldTryToFindUserByItsUsername_whenRequestItIsNewActivationFor()
    {
        $this->repository->expects($this->once())->method('findUserByUsername');
        $response= call_user_func($this->application,$this->newActivationFor);
    }
    
    function test_invoke_shouldSendTheActivationRequest_whenTheUserIsNotActived()
    { 
        $user= UserBuilder::of()->setId('aaa')->setActivation(UserActivation::newActivation())->build();
        $this->repository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $this->activation->expects($this->once())->method('sendActivationRequest');
        
        $response= call_user_func($this->application,$this->newActivationFor);
    }
    
    function test_invoke_shouldReturnResponseWithError_whenUserIsAlreadyActived()
    {
        $user=$this->createMock(User::class);
        $user->expects($this->once())
            ->method('isActived')
            ->willReturn(true);
        
        
        $this->repository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $response= call_user_func($this->application,$this->newActivationFor);
        
        $this->assertTrue($response->hasErrors());
        $this->assertEquals('Your account is already actived',$response->getErrors()['domain']);
    }
    
    
    
}
