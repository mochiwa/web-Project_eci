<?php

namespace App\Identity\Application\Response;

use App\Shared\Application\AbstractApplicationResponse;

/**
 * Description of UserActivationResponse
 *
 * @author mochiwa
 */
class UserActivationResponse extends AbstractApplicationResponse {
   
    public function __construct()
    {
       
    }

    public static function of(): self {
        return new self();
    }



}
