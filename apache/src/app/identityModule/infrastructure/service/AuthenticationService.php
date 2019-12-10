<?php

namespace App\Identity\Infrastructure\Service;

use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\User;
use App\Identity\Model\User\Username;
use Framework\Session\SessionManager;

/**
 * Description of AuthenticationService
 *
 * @author mochiwa
 */
class AuthenticationService {
    const USER_AUTHENTICATED="USER_AUTHENTICATED";
    private $userRepository;
    private $sessionManager;
    
    public function __construct(IUserRepository $userRepository, SessionManager $sessionManager) {
       $this->userRepository=$userRepository;
       $this->sessionManager=$sessionManager;
    }
    
    
    function authentication(Username $username,Password $password) : User{
        try{
            $user=$this->userRepository->findUserByUsername($username);
            
            if(!$user->isPasswordMatch($password) || $this->isUserConnected()){
                throw new AuthenticationException();
            }else{
                $this->sessionManager->set(self::USER_AUTHENTICATED, $user);
                return $user;
            }
        } catch (EntityNotFoundException $ex) {
            throw new AuthenticationException();
        }
        
    }
    
    function isUserConnected():bool
    {
        return $this->sessionManager->get(self::USER_AUTHENTICATED)!=null;
    }
    
    function logout()
    {
        if($this->isUserConnected()){
            $this->sessionManager->delete(self::USER_AUTHENTICATED);
        }
    }
}
