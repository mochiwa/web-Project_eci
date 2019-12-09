<?php

use App\Identity\Infrastructure\Service\PasswordEncryptionService;
use PHPUnit\Framework\TestCase;

/**
 * Description of PasswordEncryptionTest
 *
 * @author mochiwa
 */
class PasswordEncryptionTest extends TestCase{
    
    
    function test_crypt_shouldUseBCrypt_whenAlgoIsNotSpecified()
    {
        $clear='aPassword';
        $hashed= PasswordEncryptionService::of()->crypt($clear);
        $this->assertEquals(PASSWORD_BCRYPT, password_get_info($hashed)['algo'] );
    }
    function test_crypt_shouldEncryptThePassword()
    {
        $clear='aPassword';
        $hashed= PasswordEncryptionService::of()->crypt($clear);
        $this->assertNotEquals($clear,$hashed);
    }
    
    function test_isMatch_shouldReturnFalse_whenPasswordNotMatchWithTheHash()
    {
        $clear='aPassword';
        $hashed= PasswordEncryptionService::of()->crypt('ARandomPassword');
        $this->assertFalse(PasswordEncryptionService::of()->isMatch($clear, $hashed));
    }
    function test_isMatch_shouldReturnTrue_whenPasswordMatchWithTheHash()
    {
        $clear='aPassword';
        $hashed= PasswordEncryptionService::of()->crypt($clear);
        $this->assertTrue(PasswordEncryptionService::of()->isMatch($clear, $hashed));
    }
    
}
