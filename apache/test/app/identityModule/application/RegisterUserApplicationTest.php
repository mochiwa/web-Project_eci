<?php

use App\Identity\Application\RegisterUserApplication;
use App\Identity\Application\Request\RegisterUserRequest;
use App\Identity\Infrastructure\Service\PasswordEncryptionService;
use App\Identity\Model\User\Service\UserProviderException;
use App\Identity\Model\User\Service\UserProviderService;
use Framework\Validator\AbstractFormValidator;
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
    
    protected function setUp() {
        $this->validator=$this->createMock(AbstractFormValidator::class);
        $this->UserProvider=$this->createMock(UserProviderService::class);
        $this->passwordEncryption=$this->createMock(PasswordEncryptionService::class);
        $this->application=new RegisterUserApplication($this->validator,$this->UserProvider,$this->passwordEncryption);
        
    }
    
    
    function test_invoke_shouldReturnRegisterResponseWithValidatorErrors_whenTheValidatorIsNotValid()
    {
        $request = RegisterUserRequest::fromPost(['email'=>'email@gmail.com','username'=>'username','password'=>'password','passwordConfirmation'=>'password']);
        $this->validator->expects($this->once())->method('validate')->willReturn(false);
        $this->validator->expects($this->once())->method('getErrors')->willReturn(['field'=>'errors']);
        $response = call_user_func( $this->application,$request);
        $this->assertTrue($response->hasErrors());
        $this->assertEquals(['field'=>'errors'], $response->getErrors());
    }
    
    function test_invoke_shouldReturnRegisterResponseWithDomainErrors_whenDomainExceptionOccur()
    {
        $request = RegisterUserRequest::fromPost(['email'=>'email@gmail.com','username'=>'username','password'=>'password','passwordConfirmation'=>'password']);
        $this->validator->expects($this->once())->method('validate')->willReturn(true);
        $this->UserProvider->expects($this->once())->method('provide')->willThrowException(new UserProviderException());
        $response = call_user_func( $this->application,$request);
        $this->assertTrue($response->hasErrors());
    }
    
    function test_invoke_shouldReturnRegisterResponseWithUserView_whenNoErrorOccurs()
    {
        $user=UserBuilder::of()
            ->setEmail('email@gm.com')
            ->setUsername('username')
            ->setPassword('password')
            ->build();
        $request = RegisterUserRequest::fromPost(['email'=>'email@gmail.com','username'=>'username','password'=>'password','passwordConfirmation'=>'password']);
        $this->validator->expects($this->once())->method('validate')->willReturn(true);
        $this->UserProvider->expects($this->once())->method('provide')->willReturn($user);
        $response = call_user_func( $this->application,$request);
        $this->assertFalse($response->hasErrors());
        $this->assertEquals('email@gm.com',$response->getUserView()->getEmail());
    }
    
    
    function test_invoke_shouldEnCrypteThePassword_whenValidatorIsValidate()
    {
        $request = RegisterUserRequest::fromPost(['email@gm.com','username','password','password']);
        $this->validator->expects($this->once())->method('validate')->willReturn(true);
        $this->passwordEncryption->expects($this->once())->method('encrypt');
        
        $response = call_user_func( $this->application,$request);
    }
}
