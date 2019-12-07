<?php

namespace App\Identity\Model\User;

/**
 * Description of Email
 *
 * @author mochiwa
 */
class Email {
    /**
     * the email
     * @var string 
     */
    private $email;
    
    private function __construct(string $email)
    {
        $this->setEmail($email);
    }
    
    public static function of(string $email)
    {
        return new self($email);
    }
    
    private function setEmail(string $email)
    {
        $this->email=$email;
    }
    
    public function emailToString():string 
    {
        return $this->email;
    }
    
}
