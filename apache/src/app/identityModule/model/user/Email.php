<?php

namespace App\Identity\Model\User;

use InvalidArgumentException;

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
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            throw new InvalidArgumentException('Cannot reconnize the email format');
        }
        elseif(isset($email[100])){
             throw new InvalidArgumentException('The max lenght for email is 100');
        }
        $this->email=$email;
    }
    
    public function emailToString():string 
    {
        return $this->email;
    }
    
}
