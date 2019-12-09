<?php
namespace App\Identity\Infrastructure\Validation;

use Framework\Validator\AbstractFormValidator;

/**
 * Description of UserRegisterValidation
 *
 * @author mochiwa
 */
class UserRegisterValidation extends AbstractFormValidator{
    public function __construct(array $registerFrom=[]) {
        parent::__construct($registerFrom);
    }
    public function initRules() {
        $this->validator->rule('required', ['email','username','password','passwordConfirmation']);
        $this->validator->rule('email', 'email')->message('The email is not valid');
        $this->validator->rule('equals', 'password','passwordConfirmation')->message('Password does not match');
    }

}
