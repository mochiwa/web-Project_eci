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
        $username=trim($username);
        if(strlen(trim($username))< 3)
        {
            throw new \InvalidArgumentException("The username lenght must be atleast to 3");
        }
        elseif(strlen(trim($username))> 55)
        {
            throw new \InvalidArgumentException("The username lenght must be maximal to 55");
        }
        $this->username=$username;
    }
    
    
    public function usernameToString():string
    {
        return $this->username;
    }
    
}
