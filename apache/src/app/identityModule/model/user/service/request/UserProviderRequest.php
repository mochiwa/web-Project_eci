<?php
namespace App\Identity\Model\User\Service\Request;
/**
 * Description of UserProviderRequest
 *
 * @author mochiwa
 */
class UserProviderRequest {
    /**
     * the user email
     * @var string
     */
    private $email;
    
    /**
     * $the username of the user
     * @var string 
     */
    private $username;
    
    public function __construct(string $email,string $username) {
        $this->email = $email;
        $this->username=$username;
    }
    
    public static function of(string $email,string $username=''):self
    {
        return new self($email,$username);
    }
    
    
    public function getEmail() :string {
        return $this->email;
    }
    
    public function getUsername():string {
        return $this->username;
    }




       
    
}
