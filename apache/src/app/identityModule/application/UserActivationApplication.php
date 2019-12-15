<?php

namespace App\Identity\Application;

use App\Identity\Application\Request\AbstractUserActivationRequest;
use App\Identity\Application\Response\UserApplicationResponse;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserActivation;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\UserActivationException;
use App\Identity\Model\User\UserId;
use App\Identity\Model\User\Username;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use InvalidArgumentException;

/**
 * Description of UserActivationApplication
 *
 * @author mochiwa
 */
class UserActivationApplication extends AbstractUserApplication{
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
    
    /**
     *
     * @var SessionManager
     */
    private $sessionManager;




    public function __construct(IUserRepository $userRepository, IUserActivation $userActivation, SessionManager $sessionManager) {
        $this->userRepository = $userRepository;
        $this->userActivation = $userActivation;
        $this->sessionManager = $sessionManager;
    }

    public function __invoke(AbstractUserActivationRequest $request) : Response\UserApplicationResponse {
        if($request instanceof Request\NewActivationRequest)
        {
            return $this->activationRequest($request->getUserData());
        }elseif ($request instanceof Request\ProcessActivationRequest) {
            return $this->activationProcess($request->getUserData());
        }
        return UserApplicationResponse::of()->withError('An error occurs during your validation porcess.', 'general');
       
    }
    
    /**
     * Process the activation, if one errors is occurred then return
     * response with errors , else return response with a userView
     * @param string $id
     * @return UserApplicationResponse
     */
    private function activationProcess(string $id):Response\UserApplicationResponse{
        try{
            $user=$this->userRepository->findUserById(UserId::of($id));
            $user->active();
            $this->userRepository->updateUser($user);
            $this->sessionManager->setFlash(FlashMessage::success('Your account is actived, you can now sign in !'));
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
    
    /**
     * This method manage the send activation request through the user,
     * if the user is already actived or an error occurs during the process
     * the return a response with errors
     * @param string $username
     * @return UserApplicationResponse
     */
    private function activationRequest(string $username) : Response\UserApplicationResponse {
        try {
            $user = $this->userRepository->findUserByUsername( Username::of($username));
            if ($user->isActived()) {
                $this->errors=['domain'=>'Your account is already actived'];
            }else{
                $this->userActivation->sendActivationRequest($user);
            }
        }catch (InvalidArgumentException $ex) {
            $this->errors=['input'=>'An error occurs during your validation porcess.'];
        }catch (EntityNotFoundException $ex) {
            $this->errors=['repository'=>'An error occurs during your validation porcess.'];
        }
        return $this->buildResponse();
    }
}
