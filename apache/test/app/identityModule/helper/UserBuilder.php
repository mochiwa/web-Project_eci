<?php
namespace Test\App\Identity\Helper;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\User;
use App\Identity\Model\User\UserActivation;
use App\Identity\Model\User\UserId;
use App\Identity\Model\User\Username;
use PharIo\Manifest\Email;

/**
 * Description of UserBuilder
 *
 * @author mochiwa
 */
class UserBuilder {
    /**
     * The user Id
     * @var UserId 
     */
    private $id;
    /**
     * The user email
     * @var Email 
     */
    private $email;
    /**
     * the userName
     * @var Username 
     */
    private $username;
    /**
     * The user password
     * @var Password
     */
    private $password;
    
    /**
     * 
     * @var UserActivation 
     */
    private $activation;
    
    public function __construct() {
        $this->id = UserId::of('aaa');
        $this->email = \App\Identity\Model\User\Email::of('email@mail.com');
        $this->username = Username::of('aUserName');
        $this->password = Password::secure('aPassword');
        $this->activation = UserActivation::of(time(), time()+1);
    }

    
    
    public function setId(string $id) {
        $this->id = UserId::of($id);
        return $this;
    }

    public function setEmail(string $email) {
        $this->email = \App\Identity\Model\User\Email::of($email);
        return $this;
    }

    public function setUsername(string $username) {
        $this->username = Username::of($username);
        return $this;
    }

    public function setPassword( $password) {
        if($password instanceof Password)
            $this->password=$password;
        else
            $this->password = Password::secure($password);
        return $this;
    }

    public function setActivation( $activation) {
        $this->activation = $activation;
        return $this;
    }
    
    public static function of() :self
    {
        return new self();
    }

    public function build() : User
    {
        return new User($this->id,$this->email,$this->username,$this->password,$this->activation);
    }


    
}
