<?php

namespace App\Identity\Application\Request;

/**
 * Description of SignInRequest
 *
 * @author mochiwa
 */
class SignInRequest {
    /**
     * the username 
     * @var string 
     */
    private $username;
    /**
     * the clear text password
     * @var string
     */
    private $password;
    
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }
    
    public static function fromPost(array $post):self
    {
        return new self($post['username'] ?? '',$post['password']??'');
    }
    
    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function toArray():array
    {
        return [
            'username' => $this->username,
        ];
    }

   

    
}
