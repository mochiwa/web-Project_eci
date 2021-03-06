<?php
namespace App\Identity\Model\User\Service;

use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Service\Request\UserProviderRequest;
use App\Identity\Model\User\User;

/**
 * This service is responsible to provide
 * user account when a visitor want to sign up
 *
 * @author mochiwa
 */
class UserProviderService {
    private $userRepository;
    
    public function __construct(IUserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }
    
    public function provide(UserProviderRequest $request) : User
    {
        $email= $request->getEmail();
        $username= $request->getUsername();
        $password= $request->getPassword();
        
        if(!$this->userRepository->isEmailFree($email)){
            throw new UserProviderException('The email '.$email->emailToString().' is already used');
        }elseif(!$this->userRepository->isUsernameFree($username)){
            throw new UserProviderException('The username '.$username->usernameToString().' is already used');
        }
        elseif(!$password->isSecure())
        {
            throw new UserProviderException('The password is not secure enough');
        }
        $user= User::fromProvider($this->userRepository->nextId(),$email,$username,$password);
        $this->userRepository->addUser($user);
        return $user;
    }


}
