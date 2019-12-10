<?php

use App\Identity\Application\LogoutApplication;
use App\Identity\Application\Request\LogoutRequest;
use App\Identity\Infrastructure\Service\AuthenticationException;
use App\Identity\Infrastructure\Service\AuthenticationService;
use PHPUnit\Framework\TestCase;

/**
 * Description of LogoutApplicationTest
 *
 * @author mochiwa
 */
class LogoutApplicationTest extends TestCase{
    private $application;
    private $authentication;
    
    private $request;
    
    protected function setUp() {
        $this->authentication=$this->createMock(AuthenticationService::class);
        $this->application=new LogoutApplication($this->authentication);
        
        $this->request= LogoutRequest::of();
        
    }
    
    
    function test_invoke_shouldReturnResponseWithError_whenThereAreNoUserInSession()
    {
        $this->authentication->expects($this->once())->method('logout')->willThrowException(new AuthenticationException());
        $reponse= call_user_func($this->application,$this->request);
        
        $this->assertTrue($reponse->hasErrors());
        $this->assertEquals('You are not connected , so you cannot logout ...',$reponse->getErrors()['application']);
    }
    
}
