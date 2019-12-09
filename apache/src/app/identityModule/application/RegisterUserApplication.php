<?php
namespace App\Identity\Application;

use App\Identity\Application\Request\RegisterUserRequest;
use App\Identity\Application\Response\RegisterUserResponse;
use App\Identity\Application\Response\UserView;
use App\Identity\Model\User\Email;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\Service\Request\UserProviderRequest;
use App\Identity\Model\User\Service\UserProviderException;
use App\Identity\Model\User\Service\UserProviderService;
use App\Identity\Model\User\Username;
use Framework\Validator\AbstractFormValidator;

/**
 * Description of RegisterUserApplication
 *
 * @author mochiwa
 */
class RegisterUserApplication {
    const PASSWORD_SECURITY_REGEX='/^[a-zA-Z0-9]{3,55}$/';
    private $validator;
    private $userProvider;
    public function __construct(AbstractFormValidator $validator, UserProviderService $userProvider) {
        $this->validator=$validator;
        $this->userProvider=$userProvider;
    }
    
    /**
     * Process to the user registration, if error occurs during the form
     * validation or during the domain process, then return response with error,
     * else return RegisterUserResponse with a userView
     * 
     * @param RegisterUserRequest $request
     * @return RegisterUserResponse
     */
    public function __invoke(RegisterUserRequest $request) : RegisterUserResponse {
       if(!$this->validator->validate($request->toArray())){
            return RegisterUserResponse::of()->withErrors($this->validator->getErrors());
       }
       
       try{
           $email= Email::of($request->getEmail());
           $username= Username::of($request->getUsername());
           $password= Password::secure($request->getPassword(),self::PASSWORD_SECURITY_REGEX);           
           
          $user=$this->userProvider->provide(UserProviderRequest::of($email,$username,$password));
       } catch (UserProviderException $ex) {
           return RegisterUserResponse::of()->withError($ex->getMessage(),'domain');
       }catch(\Exception $ex)
       {
           return RegisterUserResponse::of()->withError('One problem occure during the creation of our account, please contact an administrator');
       }
       return RegisterUserResponse::of()->withUserView(UserView::fromUser($user));
       
    }
    
}
