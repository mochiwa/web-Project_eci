<?php

namespace App\Article\Model\Article;

/**
 * Description of Attribute
 *
 * @author mochiwa
 */
class Attribute {
   private $key;
   private $value;
   
   public function __construct(string $key,string $value)
   {
       $this->setKey($key);
       $this->setValue($value);
   }
   
   public static function of(string $key,string $value)
   {
       return new self($key,$value);
   }


   private function setKey(string $key)
   {
       if(strlen($key)<3){
           throw new \InvalidArgumentException ('The key lenght of an attribute must be supperior than 3');
       }
      $this->key=$key; 
   }
   private function setValue(string $value)
   {
      $this->value=$value; 
   }
  
}
