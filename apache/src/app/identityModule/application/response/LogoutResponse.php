<?php

namespace App\Identity\Application\Response;

use App\Shared\Application\AbstractApplicationResponse;

/**
 * Description of LogoutResponse
 *
 * @author mochiwa
 */
class LogoutResponse extends AbstractApplicationResponse{
    
    public static function of():self
    {
        return new self();
    }
}
