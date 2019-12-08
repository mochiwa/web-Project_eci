<?php

namespace App\Identity\Model\User;

use DateTime;

/**
 * Description of UserActivation
 *
 * @author mochiwa
 */
class UserActivation {
    private $createdDate;
    private $activedDate;
    
    public function __construct(int $createdDate,int $activedDate) {
        $this->createdDate = $createdDate;
        $this->setActivedDate($activedDate);
    }
    
    public static function of(int $createdDate,int $activedDate=0) : UserActivation
    {
        return new self($createdDate,$activedDate);
    }
    public static function newActivation() : UserActivation
    {
        return new self(time(),0);
    }
    
    private function setActivedDate(int $date)
    {
        if($date != 0 && $this->createdDate > $date ){
            throw new InvalidArgumentException("The activation date cannot be inferior than creation date");
        }
        $this->activedDate=$date;
    }
    
    
    public function isActived():bool
    {
        return $this->activedDate!=null;
    }
    
    
    public function createdDate():int 
    {
        return $this->createdDate;
    }
    
}
