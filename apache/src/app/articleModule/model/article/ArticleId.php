<?php

namespace App\Article\Model\Article;

/**
 * Description of ArticleId
 *
 * @author mochiwa
 */
class ArticleId {
    private $id;
    
    private function __construct(string $id) {
        $this->id=$id;
    }
    
    public static function of(string $id)
    {
        return new self($id);
    }
    
    private function setId(string $id)
    {
        if(empty($id)){
            throw new InvalidArgumentException("The id of an article cannot be empty !");
        }
        $this->id=$id;
    }
}
