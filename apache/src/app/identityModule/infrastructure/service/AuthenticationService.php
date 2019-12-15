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
 * Responsible to connect user into session or cookie
 *
 * @author mochiwa
 */
class AuthenticationService {
    const COOKIE_CONNECTED_USER = 'connected_user';
    const SESSION_CONNECTED_USER = SessionManager::CURRENT_USER_KEY;

    private $userRepository;
    private $sessionManager;
    private $cookieManager;

    public function __construct(IUserRepository $userRepository, SessionManager $sessionManager, CookieManager $cookieManager) {
        $this->userRepository = $userRepository;
        $this->sessionManager = $sessionManager;
        $this->cookieManager = $cookieManager;
    }

    /**
     * If the user exist,password match and no other user connected then 
     * connect the new user.
     * If The password not match or entity not found then throw AuthenticationException
     * 
     * @param Username $username
     * @param Password $password
     * @return User
     * @throws AuthenticationException
     */
    function authentication(Username $username, Password $password): User {
        try {
            $user = $this->userRepository->findUserByUsername($username);
            if ($user->isPasswordMatch($password) && !$this->isUserConnected() && $user->isActived()) {
                return $user;
            }
        } catch (EntityNotFoundException $ex) {
            throw new AuthenticationException();
        }
        throw new AuthenticationException();
    }

    /**
     * Return true is an user is present in session
     * @return bool
     */
    function isUserConnected(): bool {
        return $this->sessionManager->get(self::SESSION_CONNECTED_USER) != null;
    }

    /**
     * Logout the user , if cookie exist then erase it,
     * if any user connected throw Authentication exception
     * 
     * @throws AuthenticationException
     */
    function logout() {
        if (!$this->isUserConnected()) {
            throw new AuthenticationException();
        }
        if ($this->cookieManager->hasCookie(self::COOKIE_CONNECTED_USER)) {
            $this->cookieManager->eraseCookie(self::COOKIE_CONNECTED_USER);
        }
        $this->sessionManager->delete(SessionManager::CURRENT_USER_KEY);
    }


    /**
     * If a cookie exist then try to connect with information contained into
     * the cookie , if cookie not found or username/password not well formed
     * throw AuthenticationException .
     * @see authentication
     * @return User
     * @throws AuthenticationException
     */
    function authenticateByCookie(): User {
        try {
            $userValueFromCookie = $this->cookieManager->getDecodedValuesFromCookie(self::COOKIE_CONNECTED_USER, true);
            $username = Username::of($userValueFromCookie['username']);
            $password = Password::of($userValueFromCookie['password']);
            return $this->authentication($username, $password);
        } catch(CookieStoreException $ex) {
            throw new AuthenticationException();
        } catch (\InvalidArgumentException $ex){
            throw new AuthenticationException();
        }
    }

    /**
     * Set the user into the session at key self::SESSION_CONNECTED_USER,
     * if user already present throw Authentication exception
     * @param User $user
     * @throws AuthenticationException
     */
    function setConnectedUserInSession(User $user) {
        if ($this->sessionManager->has(self::SESSION_CONNECTED_USER)) {
            throw new AuthenticationException("An user is already present has connected user in session");
        }
        $this->sessionManager->set(self::SESSION_CONNECTED_USER, $user);
    }

    /**
     * Set the user into the cookie at key self::COOKIE_CONNECTED_USER,
     * if user already present throw Authentication exception
     * The cookie contain username => ""  and password=> ""
     * @param User $user
     * @param int $time
     * @throws AuthenticationException
     */
    function setConnectedUserInCookie(User $user, int $time = CookieManager::TIME_ONE_DAY) {
        if ($this->cookieManager->hasCookie(self::COOKIE_CONNECTED_USER)) {
            throw new AuthenticationException("An user is already present has connected user in cookie");
        }
        $userInformation = [
            'username' => $user->username()->usernameToString(),
            'password' => $user->password()->toString()
        ];
        $this->cookieManager->addCookie(self::COOKIE_CONNECTED_USER, json_encode($userInformation), $time+time(),'/');
    }

}
