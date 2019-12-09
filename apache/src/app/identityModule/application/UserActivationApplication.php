<?php

namespace App\Identity\Application;

use App\Identity\Application\Request\UserActivationRequest;
use App\Identity\Application\Response\UserActivationResponse;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserActivation;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\UserActivationException;
use App\Identity\Model\User\UserId;
use App\Identity\Model\User\Username;
use InvalidArgumentException;

/**
 * Description of UserActivationApplication
 *
 * @author mochiwa
 */
class UserActivationApplication {
    /**
     *
     * @var IUserRepository 
     */
    private $userRepository;
   
    /**
     *
     * @var IUserActivation
     */
    private $userActivation;
    
    public function __construct(IUserRepository $userRepository, IUserActivation $userActivation) {
        $this->userRepository = $userRepository;
        $this->userActivation = $userActivation;
    }

    
    
    
    
    public function __invoke(UserActivationRequest $request) : UserActivationResponse {
        if(empty($request->getUsername()) && empty($request->getId()))
        {
            return UserActivationResponse::of()->withError('An error occurs during your validation porcess.', 'general');
        }
        
        if(!empty($request->getUsername()))
        {
            return $this->returnActivationRequest($request->getUsername());
        }
        
        
        
       /* try{
            $userId= UserId::of($request->getId());
        } catch (InvalidArgumentException $ex) {
            return UserActivationResponse::of()->withError('An error occurs during your validation porcess.', 'general');
        }
        
        try{
            $user=$this->userRepository->findUserById($userId);
            $user->active();
            $this->userRepository->updateUser($user);
        } catch (EntityNotFoundException $ex) {
            return UserActivationResponse::of()->withError('An error occurs during your validation porcess.', 'general');
        } catch (UserActivationException $ex)
        {
            return UserActivationResponse::of()->withError('Your account is already actived', 'general');
        }
        return UserActivationResponse::of();*/
    }
    
    
    private function returnActivationRequest(string $username) : UserActivationResponse {
        try {
            $username = Username::of($username);
            $user = $this->userRepository->findUserByUsername($username);
            if ($user->isActived()) {
                return UserActivationResponse::of()->withError('Your account is already actived', 'general');
            }
            $this->userActivation->sendActivationRequest($user);
        }catch (InvalidArgumentException $ex) {
            return UserActivationResponse::of()->withError('An error occurs during your validation porcess. is your username valid ?', 'general');
        }catch (EntityNotFoundException $ex) {
            return UserActivationResponse::of()->withError('An error occurs during your validation process', 'general');
        }
        
        return UserActivationResponse::of();
    }

}
