<?php
namespace App\Identity\Model\User\Service\Request;
/**
 * Description of UserProviderRequest
 *
 * @author mochiwa
 */
class UserProviderRequest {
    /**
     * The user email
     * @var string
     */
    private $email;
    
    /**
     * The username of the user
     * @var string 
     */
    private $username;
    
    /**
     * The user password
     * @var string 
     */
    private $password;
    
    private function __construct(string $email,string $username,string $password) {
        $this->email = $email;
        $this->username=$username;
        $this->password=$password;
    }
    
    public static function of(string $email,string $username='',string $password=''):self
    {
        return new self($email,$username,$password);
    }
    
    
    public function getEmail() :string {
        return $this->email;
    }
    
    public function getUsername():string {
        return $this->username;
    }
    
    public function getPassword() {
        return $this->password;
    }






       
    
}
