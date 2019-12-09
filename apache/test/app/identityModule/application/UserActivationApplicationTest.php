<?php

use App\Identity\Application\Request\UserActivationRequest;
use App\Identity\Application\UserActivationApplication;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserActivation;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\User;
use App\Identity\Model\User\UserActivation;
use App\Identity\Model\User\UserActivationException;
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
    
    private $processRequest;
    private $newActivationFor;
    
    function setUp() {
        $this->repository=$this->createMock(IUserRepository::class);
        $this->activation=$this->createMock(IUserActivation::class);
        $this->application=new UserActivationApplication($this->repository,$this->activation);
        
        
        $this->processRequest=UserActivationRequest::of('anId');
        $this->newActivationFor= UserActivationRequest::newActivationFor('aUsername');
    }
    
    
    function test_invoke_shouldReturnResponseWithError_whenIdAndUsernameFromRequestIsEmpty()
    {
        $request=UserActivationRequest::of('');
        $response= call_user_func($this->application,$request);
        $this->assertTrue($response->hasErrors());
        $this->assertEquals('An error occurs during your validation porcess.',$response->getErrors()['general']);
    }
    
    function test_invoke_shouldTryToFindUserByItsId_whenTheUsernameIsEmpty()
    {
        $this->repository->expects($this->once())->method('findUserById');
        $response= call_user_func($this->application,$this->processRequest);
    }
    
    function test_invoke_shouldReturnResponseWithError_whenIdNotFoundInRepository()
    {
        $this->repository->expects($this->once())->method('findUserById')->willThrowException(new EntityNotFoundException());
        
        $response= call_user_func($this->application,$this->processRequest);
        $this->assertTrue($response->hasErrors());
        $this->assertEquals('An error occurs during your validation porcess.',$response->getErrors()['general']);
    }
    
    function test_invoke_shouldReturnResponseWithError_whenUserIsAlreadyValidate()
    {
        $user= $this->createMock(User::class); 
        $this->repository->expects($this->once())->method('findUserById')->willReturn($user);
        $user->expects($this->once())->method('active')->willThrowException(new UserActivationException());
        
        $response= call_user_func($this->application,$this->processRequest);
        $this->assertTrue($response->hasErrors());
        $this->assertEquals('Your account is already actived',$response->getErrors()['general']);
    }
    
    function test_invoke_shouldUpdateUser_whenUserIsNotAlreadyValidate()
    {
        $user= UserBuilder::of()->setId('aaa')->setActivation(UserActivation::newActivation())->build();
        $this->repository->expects($this->once())->method('findUserById')->willReturn($user);
        $this->repository->expects($this->once())->method('updateUser');
        
        $response= call_user_func($this->application,$this->processRequest);
        
        $this->assertFalse($response->hasErrors());
        $this->assertTrue($user->isActived());
    }
    
    function test_invoke_shouldTryToFindUserByItsUsername_whenTheIdIsEmpty()
    {
        $this->repository->expects($this->once())->method('findUserByUsername');
        $response= call_user_func($this->application,$this->newActivationFor);
    }
    
    function test_invoke_shouldReturnResponseWithValidationLink_whenTheUserIsNotActived()
    { 
        $user= UserBuilder::of()->setId('aaa')->setActivation(UserActivation::newActivation())->build();
        $this->repository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $this->activation->expects($this->once())->method('sendActivationRequest')->willReturn('/user/activation-aaa');
        
        $response= call_user_func($this->application,$this->newActivationFor);
        $this->assertTrue($response->hasLink());
        $this->assertEquals('/user/activation-aaa', $response->getLink());
    }
    
    function test_invoke_shouldReturnResponseWithValidationInstructionMessage_whenTheUserIsNotActived()
    { 
        $user= UserBuilder::of()->setId('aaa')->setActivation(UserActivation::newActivation())->build();
        $this->repository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $this->activation->expects($this->once())->method('sendActivationRequest')->willReturn('/user/activation-aaa');
        $this->activation->expects($this->once())->method('getMessage')->willReturn('a message');
        
        $response= call_user_func($this->application,$this->newActivationFor);
        $this->assertTrue($response->hasLink());
        $this->assertNotEmpty($response->getInstruction());
    }
    
    
    function test_invoke_shouldReturnResponseWithError_whenUserIsAlreadyActived()
    {
        $user= UserBuilder::of()->setId('aaa')->setActivation(UserActivation::of(1,2))->build();
        $this->repository->expects($this->once())->method('findUserByUsername')->willReturn($user);
        $response= call_user_func($this->application,$this->newActivationFor);
        
        $this->assertTrue($response->hasErrors());
        $this->assertEquals('An error occurs during your validation porcess.',$response->getErrors()['general']);
    }
    
    
    
}
