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
                $this->sessionManager->set(SessionManager::CURRENT_USER_KEY, $user);
                return $user;
            }
        } catch (EntityNotFoundException $ex) {
            throw new AuthenticationException();
        }
        
    }
    
    function isUserConnected():bool
    {
        return $this->sessionManager->get(SessionManager::CURRENT_USER_KEY)!=null;
    }
    
    function logout()
    {
        if(!$this->isUserConnected()){
            throw new AuthenticationException();
        }
        $this->sessionManager->delete(SessionManager::CURRENT_USER_KEY);
    }
}
