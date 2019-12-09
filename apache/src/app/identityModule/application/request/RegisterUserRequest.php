<?php
namespace App\Identity\Application\Request;
/**
 * Description of RegisterUserRequest
 *
 * @author mochiwa
 */
class RegisterUserRequest {
    private $email;
    private $username;
    private $password;
    private $passwordConfirmation;
    
    private function __construct($email, $username, $password, $passwordConfirmation) {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }
    
    public static function fromPost(array $post):self
    {
        return new self(
            $post['email'] ?? '',
            $post['username'] ?? '',
            $post['password'] ?? '',
            $post['passwordConfirmation'] ?? '');
    }
    
    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPasswordConfirmation() {
        return $this->passwordConfirmation;
    }
    
    public function toArray():array
    {
        return [
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'passwordConfirmation' => $this->passwordConfirmation
        ];
    }



    
    
}
