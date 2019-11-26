<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Model\Article;

/**
 * Description of Article
 *
 * @author mochiwa
 */
class Article {
    private $id;
    private $title;
    private $picture;
    private $attributes;
    private $description;
    private $creationDate;
    private $lastUpdateDate;
    
    private function __construct(ArticleId $id,Title $title,
            Picture $picture,array $attributes,string $description,
            Date $creationDate, Date $lastUpdateDate) 
    {
        $this->id = $id;
        $this->title = $title;
        $this->picture = $picture;
        $this->attributes = $attributes;
        $this->description = $description;
        $this->creationDate = $creationDate;
        $this->lastUpdateDate = $lastUpdateDate;
    }

    
    public static function newArticle(
            ArticleId $id, 
            Title $title,
            Picture $picture,
            array $attributes,
            string $description)
    {
        $currentTime=Date::fromTimeStamp(time());
        return new self($id,$title,$picture,$attributes,$description, $currentTime,$currentTime);
    }
    
    
    public function id() : ArticleId
    {
        return $this->id;
    }
    
}
