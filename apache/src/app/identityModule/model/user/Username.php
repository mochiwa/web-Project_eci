<?php

namespace App\Identity\Model\User;

/**
 * Description of Username
 *
 * @author mochiwa
 */
class Username {
    /**
     * The username
     * @var string 
     */
    private $username;
    
    private function __construct(string $username) {
        $this->setUsername($username);
    }
    
    
    public static function of(string $username):self
    {
        return new self($username);
    }
    
    private function setUsername(string $username)
    {
        $this->username=$username;
    }
    
    
    public function usernameToString():string
    {
        return $this->username;
    }
    
}
