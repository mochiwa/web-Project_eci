<?php
namespace App\Identity\Application\Response;

use App\Identity\Model\User\User;

/**
 * Description of UserView
 *
 * @author mochiwa
 */
class UserView {
    
    /**
     *
     * @var string 
     */
    private $username;
    /**
     *
     * @var string 
     */
    private $email;
    
    public function __construct(string $username,string $email) {
        $this->username = $username;
        $this->email = $email;
    }
    
    public static function fromUser(User $user) : self
    {
        return new self($user->username()->usernameToString(),$user->email()->emailToString());
    }
    public static function empty()
    {
        return new self('','');
    }
    
    public function getUsername() {
        return htmlentities($this->username);
    }

    public function getEmail() {
        return htmlentities($this->email);
    }



    
}
