<?php

namespace App\Identity\Model\User;

/**
 * Description of Password
 *
 * @author mochiwa
 */
class Password {
    const DEFAULT_SECURE_REGEX='/^.{3,55}$/';
    private $password;
    private $securityRegex;
    
    private function __construct(string $password,string $securityRegex) {
        $this->password = $password;
        $this->securityRegex = $securityRegex;
    }

    
   public static function secure(string $password,string $regex= Password::DEFAULT_SECURE_REGEX) : self{
       return new self($password,$regex);
   }
   
   
   public function isSecure():bool
   {
       return preg_match($this->securityRegex, $this->password);
   }
   
   public function toString()
   {
       return $this->password;
   }
   
}
