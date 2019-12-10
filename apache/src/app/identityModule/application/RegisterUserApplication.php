<?php

namespace App\Identity\Application;

use App\Identity\Application\Request\RegisterUserRequest;
use App\Identity\Application\Response\RegisterUserResponse;
use App\Identity\Application\Response\UserView;
use App\Identity\Infrastructure\Service\PasswordEncryptionService;
use App\Identity\Model\User\Email;
use App\Identity\Model\User\Password;
use App\Identity\Model\User\Service\Request\UserProviderRequest;
use App\Identity\Model\User\Service\UserProviderException;
use App\Identity\Model\User\Service\UserProviderService;
use App\Identity\Model\User\Username;
use Framework\Validator\AbstractFormValidator;
use Framework\Validator\ValidatorException;

/**
 * Description of RegisterUserApplication
 *
 * @author mochiwa
 */
class RegisterUserApplication {

    const PASSWORD_SECURITY_REGEX = '/^[a-zA-Z0-9]{3,100}$/';

    /**
     * The form validator 
     * @var AbstractFormValidator 
     */
    private $validator;
    /**
     * The user provider service from Domain
     * @var UserProviderService 
     */
    private $userProvider;
    /**
     * service responsible to encrypt password
     * @var PasswordEncryptionService 
     */
    private $passwordEncryption;
    
    /**
     * if errors occurs response will contain this array
     * @var array 
     */
    private $errors;
    /**
     * The userView that response will contain
     * @var type 
     */
    private $userView;

    public function __construct(AbstractFormValidator $validator, UserProviderService $userProvider, PasswordEncryptionService $passwordEncryption) {
        $this->validator = $validator;
        $this->userProvider = $userProvider;
        $this->passwordEncryption = $passwordEncryption;
    }

    /**
     * Process to the user registration, if error occurs during the form
     * validation or during the domain process, then return response with error,
     * in any case return a response withe a userView before domain if error occurs
     * else  after domain action
     * 
     * @param RegisterUserRequest $request
     * @return RegisterUserResponse
     */
    public function __invoke(RegisterUserRequest $request): RegisterUserResponse {
        try {
            $this->userView = UserView::fromArray($request->toArray());
            $this->validator->validateOrThrow($request->toArray());

            $email = Email::of($request->getEmail());
            $username = Username::of($request->getUsername());
            $password = Password::secure($this->passwordEncryption->crypt($request->getPassword()), self::PASSWORD_SECURITY_REGEX);

            $user = $this->userProvider->provide(UserProviderRequest::of($email, $username, $password));
            $this->userView = UserView::fromUser($user);
            
        } catch (ValidatorException $invalidForm) {
            $this->errors = $invalidForm->getErrors();
        } catch (UserProviderException $domainErrors) {
            $this->errors = ['domaine' => $domainErrors->getMessage()];
        } catch (InvalidArgumentException $inputException) {
            $this->errors = ['input' => $inputException->getMessage()];
        } catch (\Exception $ex) {
            $this->errors = ['general' => 'One problem occures during your account creation process, please contact an administrator'];
        } finally {
            return $this->buildResponse();
        }
    }

    
    private function buildResponse(): RegisterUserResponse {
        $response = RegisterUserResponse::of();
        if (!empty($this->errors)) {
            $response->withErrors($this->errors);
        }
        if (isset($this->userView)) {
            $response->withUserView($this->userView);
        }
        return $response;
    }

}
