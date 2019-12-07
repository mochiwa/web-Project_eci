<?php
namespace App\Identity\Model\User\Service;

use App\Identity\Model\User\Email;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\Service\Request\UserProviderRequest;
use App\Identity\Model\User\Username;

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
    
    public function provide(UserProviderRequest $request)
    {
        $email= Email::of($request->getEmail());
        $username= Username::of($request->getUsername());
        
        if(!$this->userRepository->isEmailFree($email)){
            throw new UserCredentialException('The email '.$email->emailToString().' is already used');
        }elseif(!$this->userRepository->isUsernameFree($username)){
            throw new UserCredentialException('The username '.$username->usernameToString().' is already used');
        }
    }
    
    


}
