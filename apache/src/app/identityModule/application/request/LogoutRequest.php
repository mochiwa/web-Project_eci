<?php

namespace App\Identity\Application\Request;

/**
 * Description of LogoutRequest
 *
 * @author mochiwa
 */
class LogoutRequest{
    
    public static function of():self
    {
        return new self();
    }
    
    
}
