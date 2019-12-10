<?php

namespace App\Identity\Application;

use App\Identity\Application\Request\AbstractUserActivationRequest;
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
    
    
    private $errors;
    private $userView;
    
    public function __construct(IUserRepository $userRepository, IUserActivation $userActivation) {
        $this->userRepository = $userRepository;
        $this->userActivation = $userActivation;
    }

    
    
    
    
    public function __invoke(AbstractUserActivationRequest $request) : UserActivationResponse {
        if($request instanceof Request\NewActivationRequest)
        {
            return $this->activationRequest($request->getUserData());
        }elseif ($request instanceof Request\ProcessActivationRequest) {
            return $this->activationProcess($request->getUserData());
        }
        return UserActivationResponse::of()->withError('An error occurs during your validation porcess.', 'general');
       
    }
    
    /**
     * Process the activation, if one errors is occurred then return
     * response with errors , else return response with a userView
     * @param string $id
     * @return UserActivationResponse
     */
    private function activationProcess(string $id):UserActivationResponse{
        try{
            $user=$this->userRepository->findUserById(UserId::of($id));
            $user->active();
            $this->userRepository->updateUser($user);
            $this->userView= Response\UserView::fromUser($user);
        } catch (InvalidArgumentException $invalidId) {
            $this->errors=['generale'=>"An error occurs during your validation process."];
        } catch (EntityNotFoundException $entity) {
            $this->errors=['repository'=>"Your account was not found"];
        }catch (UserActivationException $activationProcess){
            $this->errors=['activation'=>"Your account is already actived"];
        }finally {
            return $this->buildResponse();
        }
    }
    
    
    private function activationRequest(string $username) : UserActivationResponse {
        try {
            $user = $this->userRepository->findUserByUsername( Username::of($username));
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
    
    
    private function buildResponse(): UserActivationResponse
    {
        $response= UserActivationResponse::of();
        if(!empty($this->errors)){
            $response->withErrors($this->errors);
        }
        if(isset($this->userView)){
            $response->withUserView($this->userView);
        }
        return $response;
    }

}
