<?php

use App\Identity\Model\User\Password;
use PHPUnit\Framework\TestCase;

/**
 * Description of PasswordTest
 *
 * @author mochiwa
 */
class PasswordTest extends TestCase{
    
    function test_isSecure_shouldReturnTrue_whenPasswordMatchWithTheSecurityRegex()
    {
        $password=Password::secure('aGoodPassword45');
    
        $this->assertTrue($password->isSecure());
    }
    function test_isSecure_shouldReturnFalse_whenPasswordNotMatchTheSecureRegex()
    {
        $secure_regex='[0-9]';
        $password=Password::secure('aGoodPassword45',$secure_regex);
    
        $this->assertFalse($password->isSecure());
    }
    
    
}
