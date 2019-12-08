<?php

use App\Identity\Model\User\UserActivationException;
use App\Identity\Model\User\UserActivation;
use PHPUnit\Framework\TestCase;
use Test\App\Identity\Helper\UserBuilder;

/**
 * Description of UserTest
 *
 * @author mochiwa
 */
class UserTest extends TestCase{
    
    
    function test_active_shouldThrowUserActivationException_whenUserIsAlreadyActived()
    {
        $activation=$this->createMock(UserActivation::class);
        $user= UserBuilder::of()->setActivation($activation)->build();
        $activation->expects($this->once())->method('isActived')->willReturn(true);
        $this->expectException(UserActivationException::class);
        $user->active();
    }
    
    
    function test_active_ShouldSetTheUserAsActive_whenHeIsNotYetActived()
    {
        $user= UserBuilder::of()->setActivation(UserActivation::newActivation())->build();
        $user->active();
        $this->assertTrue($user->isActived());
    }
  
    
}
