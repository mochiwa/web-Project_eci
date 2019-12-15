<?php
namespace App\Identity\Infrastructure\Service;

/**
 * This class is a facade for the password encryption
 * provide by PHP.
 * 
 * By default if it not specified this facade use PASSWORD_BCRYPT
 * encryption algorithm.
 *
 * @author mochiwa
 */
class PasswordEncryptionService {
    CONST DEFAULT=PASSWORD_BCRYPT;
    
    private $algo;
    
    private function __construct(string $algo) {
        $this->algo=$algo;
    }
    
    public static function of($algo= self::DEFAULT):self
    {
        return new self($algo);
    }
    
    /**
     * Encrypt the password and return the hash
     * @param string $password
     * @return string
     */
    function crypt(string $password) :string {
        return password_hash($password, $this->algo);
    }
    /**
     * Return true if clearPasswod match the hash
     * @param type $password
     * @param type $hash
     * @return bool
     */
    function isMatch($password,$hash):bool
    {
        return password_verify($password, $hash);
    }
}
