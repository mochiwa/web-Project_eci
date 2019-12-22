<?php

namespace App\Article\Model\Article;

/**
 * Represent an Id for an article.
 * An id cannot be empty
 * @author mochiwa
 */
class ArticleId {
    private $id;
    
    private function __construct(string $id) {
        $this->setId($id);
    }
    
    public static function of(string $id)
    {
        return new self($id);
    }
    
    /**
     * @param string $id
     * @throws InvalidArgumentException when the id in parameter is empty
     */
    private function setId(string $id)
    {
        if(empty($id)){
            throw new \InvalidArgumentException("The id of an article cannot be empty !");
        }
        $this->id=$id;
    }
    
    /**
     * Transform the id to a string
     * @return string
     */
    public function idToString(): string
    {
        return $this->id;
    }
}
