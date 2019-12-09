<?php

use App\Identity\Infrastructure\Service\ActivationByLink;
use Framework\Router\IRouter;
use Framework\Session\SessionManager;
use PHPUnit\Framework\TestCase;
use Test\App\Identity\Helper\UserBuilder;

/**
 * Description of ActivationByLink
 *
 * @author mochiwa
 */
class ActivationByLinkTest extends TestCase{
    private $router;
    private $sessionManager;
    private $activation;
    protected function setUp() {
        $this->router=$this->createMock(IRouter::class);
        $this->sessionManager=$this->createMock(SessionManager::class);
        $this->activation=new ActivationByLink($this->router,$this->sessionManager);
    }
    
    function test_sendActivation_shouldGenerateALinklTo_activation()
    {
        $user = UserBuilder::of()->setId('aaa')->build();
        
        
        $this->router->expects($this->once())->method('generateURL')->willReturn('activation-aaa');
        
        $this->assertSame('activation-aaa',$this->activation->sendActivationRequest($user));
    }
    
    function test_ShouldAppendInstructionToFlashMessageIntoSession_whenSendActivationIsCalled()
    {
        $user = UserBuilder::of()->setId('aaa')->build();
        $this->sessionManager->expects($this->once())->method('setFlash');
        $this->activation->sendActivationRequest($user);
                
    }
    
}
