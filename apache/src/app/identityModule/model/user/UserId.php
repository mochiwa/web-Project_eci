<?php

namespace App\Identity\Model\User;

/**
 * This Value object represent 
 * An user id
 *
 * @author mochiwa
 */
class UserId{
    private $id;
    
    private function __construct(string $id) {
        $this->setId($id);
    }
    
    private function setId(string $id)
    {
        if(empty($id))
        {
            throw new \InvalidArgumentException("An Id cannot be empty");
        }
        $this->id=$id;
    }
            
    
    public static function of(string $id):self
    {
        return new self($id);
    }
    
    public function idToString():string
    {
        return $this->id;
    }

}
