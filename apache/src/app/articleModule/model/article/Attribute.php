<?php

namespace App\Article\Model\Article;

/**
 * Description an attribute with a value
 * like city : Belgium 
 *
 * @author mochiwa
 */
class Attribute {
   private $key;
   private $value;
   
   private function __construct(string $key,string $value)
   {
       $this->setKey($key);
       $this->setValue($value);
   }
   
   /**
    * Return a new attribute
    * @param string $key
    * @param string $value
    * @return \self
    */
   public static function of(string $key,string $value)
   {
       return new self($key,$value);
   }

   /**
    * 
    * @param string $key
    * @throws \InvalidArgumentException when the length of the key is less than 3
    */
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
   
   public function keyToString():string
   {
       return $this->key;
   }
   public function valueToString():string
   {
       return $this->value;
   }
  
}
