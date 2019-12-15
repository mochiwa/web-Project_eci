<?php
namespace App\Identity\Model\User\Service\Request;

use App\Identity\Model\User\Email;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\Username;
/**
 * Description of UserProviderRequest
 *
 * @author mochiwa
 */
class UserProviderRequest {
    /**
     * The user email
     * @var Email
     */
    private $email;
    
    /**
     * The username of the user
     * @var Username 
     */
    private $username;
    
    /**
     * The user password
     * @var Password 
     */
    private $password;
    
    private function __construct(Email $email, Username $username, Password $password) {
        $this->email = $email;
        $this->username=$username;
        $this->password=$password;
    }
    
    public static function of(Email $email,Username $username,Password $password):self
    {
        return new self($email,$username,$password);
    }
    
    
    public function getEmail() : Email {
        return $this->email;
    }
    
    public function getUsername(): Username{
        return $this->username;
    }
    
    public function getPassword(): Password {
        return $this->password;
    }






       
    
}
