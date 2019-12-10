<?php

use App\Identity\Application\RegisterUserApplication;
use App\Identity\Application\Request\RegisterUserRequest;
use App\Identity\Application\Response\UserView;
use App\Identity\Infrastructure\Service\PasswordEncryptionService;
use App\Identity\Model\User\Service\UserProviderException;
use App\Identity\Model\User\Service\UserProviderService;
use Framework\Validator\AbstractFormValidator;
use Framework\Validator\ValidatorException;
use PHPUnit\Framework\TestCase;
use Test\App\Identity\Helper\UserBuilder;

/**
 * Description of RegisterUserApplicationTest
 *
 * @author mochiwa
 */
class RegisterUserApplicationTest extends TestCase{
    private $validator;
    private $UserProvider;
    private $application;
    private $passwordEncryption;
    private $registerRequest;
    
    protected function setUp() {
        $this->validator=$this->createMock(AbstractFormValidator::class);
        $this->UserProvider=$this->createMock(UserProviderService::class);
        $this->passwordEncryption=$this->createMock(PasswordEncryptionService::class);
        $this->application=new RegisterUserApplication($this->validator,$this->UserProvider,$this->passwordEncryption);
        $this->registerRequest= RegisterUserRequest::fromPost(
            ['email'=>'email@gmail.com',
            'username'=>'username','password'=>'password',
            'passwordConfirmation'=>'password']);
    }
    
    
    function test_invoke_shouldReturnRegisterResponseWithValidatorErrors_whenTheValidatorIsNotValid()
    {
        $validatorException=$this->createMock(ValidatorException::class);
        $validatorException->expects($this->once())->method('getErrors')->willReturn(['field'=>'errors']);
        
        $this->validator->expects($this->once())
                ->method('validateOrThrow')
                ->willThrowException($validatorException);
        //$this->validator->expects($this->once())->method('getErrors')->willReturn(['field'=>'errors']);
        
        $response = call_user_func( $this->application,$this->registerRequest);
        
        $this->assertTrue($response->hasErrors());
        $this->assertEquals(['field'=>'errors'], $response->getErrors());
    }
    function test_invoke_shouldReturnRegisterResponseWithUserFormInput_whenTheValidatorIsNotValid()
    {
        $this->validator->expects($this->once())->method('validateOrThrow')->willThrowException(new ValidatorException());
        
        $response = call_user_func( $this->application,$this->registerRequest);
       
        $userViewExpected= UserView::fromArray($this->registerRequest->toArray());
        $this->assertEquals($userViewExpected, $response->getUserView());
    }
    
    function test_invoke_shouldReturnRegisterResponseWithDomainErrors_whenDomainExceptionOccur()
    {
        $this->validator->expects($this->once())->method('validateOrThrow')->willReturn(true);
        $this->UserProvider->expects($this->once())->method('provide')->willThrowException(new UserProviderException());
        
        $response = call_user_func( $this->application,$this->registerRequest);
        $this->assertTrue($response->hasErrors());
    }
    
    function test_invoke_shouldReturnRegisterResponseWithUserView_whenNoErrorOccurs()
    {
        $user=UserBuilder::of()
            ->setEmail('email@gm.com')
            ->setUsername('username')
            ->setPassword('password')
            ->build();
        $this->validator->expects($this->once())->method('validateOrThrow')->willReturn(true);
        $this->UserProvider->expects($this->once())->method('provide')->willReturn($user);
        
        $response = call_user_func( $this->application,$this->registerRequest);
        
        $userViewExpected= UserView::fromUser($user);
        $this->assertEquals($userViewExpected,$response->getUserView());
    }
    
    
    function test_invoke_shouldEnCrypteThePassword_whenValidatorIsValidate()
    {
        $this->validator->expects($this->once())->method('validateOrThrow')->willReturn(true);
        $this->passwordEncryption->expects($this->once())->method('crypt');
        
        $response = call_user_func( $this->application,$this->registerRequest);
    }
}
