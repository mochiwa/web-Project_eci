<?php

namespace App\Identity\Application\Request;

/**
 * Description of UserActivationRequest
 *
 * @author mochiwa
 */
class UserActivationRequest {
    /**
     * @var string 
     */
    private $id;
    
    /**
     *
     * @var string 
     */
    private $username;
    
    private function __construct(string $id,string $username) {
        $this->id=$id;
        $this->username=$username;
    }
    
    public static function of(string $id):self{
        return new self($id,'');
    }
    
    public static function newActivationFor(string $username):self
    {
        return new self('',$username);
    }
    

    public function getId(): string {
        return $this->id;
    }

    public function getUsername() : string {
        return $this->username;
    }



    
    
}
