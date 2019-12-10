<?php

namespace App\Identity\Application\Request;

/**
 * Description of UserActivationRequest
 *
 * @author mochiwa
 */
abstract class AbstractUserActivationRequest {
    
    /**
     * The user data where ActivationApplication used to find the user
     * @var string 
     */
    protected $userData;
    
    protected  function __construct(string $userData)
    {
        $this->userData=$userData;
    }
    
    public function getUserData():string
    {
        return $this->userData;
    }
        
}
    


