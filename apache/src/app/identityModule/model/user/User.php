<?php

namespace App\Identity\Model\User;


/**
 * Description of User
 *
 * @author mochiwa
 */
class User {
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
    
    /**
     * Todo : will be refactored later with the integrationProject
     * @var bool 
     */
    private $isAdmin;
 
    public function __construct(UserId $id,Email $email, Username $username, Password $password, UserActivation $activation,bool $isAdmin=false) {
        $this->id=$id;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->activation=$activation;
        $this->isAdmin=$isAdmin;
    }
    
    public static function of(UserId $id,Email $email, Username $username, Password $password, UserActivation $activation) : self
    {
        return self($id,$email,$username,$password,$activation);
    }
    
    public static function fromProvider(UserId $id,Email $email, Username $username, Password $password): self
    {
        return new self($id,$email,$username,$password, UserActivation::newActivation());
    }

    public function isActived():bool
    {
        return $this->activation->isActived();
    }
    public function active()
    {
        if($this->isActived()){
            throw new UserActivationException("This account is already actived", UserActivationException::USER_ALREADY_ACTIVED);
        }
        $this->activation=$this->activation->active();
    }
    
    public function id():UserId
    {
        return $this->id;
    }
    
    public function email():Email
    {
        return $this->email;
    }
    
    public function username():Username
    {
        return $this->username;
    }    
    
    public function isPasswordMatch(Password $clearPassword):bool
    {
        return  $this->password->isMatch($clearPassword) || $this->password->isEquals($clearPassword);//password_verify($clearPassword->toString(), $this->password->toString());
    }
    
    public function password(): Password{
        return $this->password;
    }
    
    /**
     * TODO : implement
     * @return bool
     */
    public function isAdmin():bool
    {
        return $this->isAdmin;
    }
}
