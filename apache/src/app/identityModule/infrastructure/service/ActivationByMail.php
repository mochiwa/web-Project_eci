<?php

namespace App\Identity\Infrastructure\Service;

use App\Identity\Model\User\IUserActivation;

/**
 * Description of ActivationByMail
 *
 * @author mochiwa
 */
class ActivationByMail implements IUserActivation{
    
    public function sendActivationRequest(\App\Identity\Model\User\User $user): string {
        return '';
    }

}
