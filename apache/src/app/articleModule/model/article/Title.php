<?php
namespace App\Article\Model\Article ;
/**
 * Description of Title
 *
 * @author mochiwa
 */
class Title {
    private $title;
    
    private function __construct(string $title) {
        $this->setTitle($title);
    }
    public static function of(string $title)
    {
        return new self($title);
    }


    private function setTitle(string $title)
    {
        if(strlen($title)<3 ||strlen($title)>50)
            throw new \InvalidArgumentException("The title lenght must be between 3 and 50");
        $this->title=$title;
    }
    
}
