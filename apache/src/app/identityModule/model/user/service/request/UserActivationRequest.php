<?php

namespace App\Identity\Model\User\Service\Request;

/**
 * Description of UserActivationRequest
 *
 * @author mochiwa
 */
class UserActivationRequest {
    /**
     *
     * @var string 
     */
    private $userId;
    
    private function __construct(string $userId) {
        $this->userId = $userId;
    }
    
    public static function of(string $userId) :self
    {
        return new self($userId);
    }


    public function getUserId() :string {
        return $this->userId;
    }



}
