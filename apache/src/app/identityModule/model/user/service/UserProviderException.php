<?php

namespace App\Identity\Model\User\Service;

use Exception;

/**
 * Description of UserCredentialException
 *
 * @author mochiwa
 */
class UserProviderException extends Exception{
    
    public function __construct(string $message) {
        parent::__construct($message);
    }
}
