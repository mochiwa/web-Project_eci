<?php

namespace App\Identity\Infrastructure\Service;

use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\User;
use App\Identity\Model\User\Username;
use Framework\Cookie\CookieManager;
use Framework\Cookie\CookieStoreException;
use Framework\Session\SessionManager;

/**
 * Description of AuthenticationService
 *
 * @author mochiwa
 */
class AuthenticationService {
    const COOKIE_CONNECTED_USER='connected_user';
    private $userRepository;
    private $sessionManager;
    private $cookieManager;
    
    public function __construct(IUserRepository $userRepository, SessionManager $sessionManager, CookieManager $cookieManager) {
       $this->userRepository=$userRepository;
       $this->sessionManager=$sessionManager;
       $this->cookieManager=$cookieManager;
    }
    
    
    function authentication(Username $username,Password $password) : User{
        try{
            $user=$this->userRepository->findUserByUsername($username);
            
            if(!$user->isPasswordMatch($password) || $this->isUserConnected()){
                throw new AuthenticationException();
            }else{
                $this->sessionManager->set(SessionManager::CURRENT_USER_KEY, $user);
                $this->buildCookie($user);
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
        if($this->cookieManager->hasCookie(self::COOKIE_CONNECTED_USER)){
            $this->cookieManager->eraseCookie(self::COOKIE_CONNECTED_USER);
        }
    }
    
    function buildCookie(User $user){
        
        if(!$this->cookieManager->hasCookie(self::COOKIE_CONNECTED_USER)){
            $userInformation=[
                'username'=>$user->username()->usernameToString(),
                'password' => $user->password()->toString()
            ];
            $this->cookieManager->addCookie(self::COOKIE_CONNECTED_USER, json_encode($userInformation), CookieManager::TIME_ONE_DAY);
        }
    }
    
    function authenticateByCookie() : User {
        try{
            $userValueFromCookie=$this->cookieManager->getDecodedValuesFromCookie(self::COOKIE_CONNECTED_USER,true);
        } catch (CookieStoreException $ex) {

        }
        
        $username= Username::of($userValueFromCookie['username']);
        $password= Password::of($userValueFromCookie['password']);
        
        return $this->authentication($username,$password);
    }
}
