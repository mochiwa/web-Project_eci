<?php

namespace App\Identity\Application\Request;

/**
 * Description of ProcessActivationRequest
 *
 * @author mochiwa
 */
class ProcessActivationRequest extends AbstractUserActivationRequest{
    
    public function __construct(string $userId) {
        parent::__construct($userId);
    }
    
    public static function of(string $userId)
    {
        return new self($userId);
    }
}