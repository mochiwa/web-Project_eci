<?php

namespace App\Identity\Model\User;

use Exception;

/**
 * Description of EntityNotFoundException
 *
 * @author mochiwa
 */
class EntityNotFoundException extends Exception{
    
    public function __construct(string $message='') {
        parent::__construct($message);
    }

}
