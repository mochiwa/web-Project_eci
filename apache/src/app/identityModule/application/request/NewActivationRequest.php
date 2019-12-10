<?php

namespace App\Identity\Application\Request;

/**
 * Description of NewActivationRequest
 *
 * @author mochiwa
 */
class NewActivationRequest extends AbstractUserActivationRequest{
    
    public function __construct(string $username) {
        parent::__construct($username);
    }
    
    public static function of(string $username)
    {
        return new self($username);
    }
}