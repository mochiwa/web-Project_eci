<?php
namespace App\Article\Model\Article ;
/**
 * A title for an article
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

    /**
     * 
     * @param string $title
     * @throws \InvalidArgumentException when the title length in argument 
     *      is not between 3 ad 50
     */
    private function setTitle(string $title)
    {
        if(strlen($title)<3 ||strlen($title)>50){
            throw new \InvalidArgumentException("The title lenght must be between 3 and 50");
        }
        $this->title=$title;
    }
    
    public function valueToString()
    {
        return $this->title;
    }
    
}
