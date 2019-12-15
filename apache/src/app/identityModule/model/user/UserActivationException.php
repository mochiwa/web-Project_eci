<?php

namespace App\Identity\Model\User;

/**
 * Description of UserActivationException
 *
 * @author mochiwa
 */
class UserActivationException extends \Exception{
    const USER_NOT_FOUND=1;
    const USER_ALREADY_ACTIVED=2;
}
