<?php
namespace App\Article\Model\Article\Service\Response;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Date;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Title;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticleView
 *
 * @author mochiwa
 */
class ArticleView {
    /**
     *
     * @var ArticleId the article id
     */
    private $id;
    /**
     *
     * @var Title the article title
     */
    private $title;
    /**
     *
     * @var Picture picture of the article
     */
    private $picture;
    /**
     *
     * @var Array list of attributes @see Attribute 
     */
    private $attributes;
    /**
     *
     * @var string description of the article
     */
    private $description;
    /**
     *
     * @var Date timestamp about the creation article 
     */
    private $creationDate;
    /**
     *
     * @var Date timestamp for the last update 
     */
    private $lastUpdateDate;
    
    public function __construct(Article $article) {
        $this->id=$article->id()->idToString();
        $this->title=$article->title()->valueToString();
        $this->picture=$article->picture()->path();
        $this->creationDate=$article->creationDate()->toHumainReadableShort();
        $this->lastUpdateDate=$article->lastUpdateDate()->toHumainReadableShort();
        $this->description=$article->description();
        foreach ($article->attributes() as $attribute) {
            $this->attributes[$attribute->keyToString()]=$attribute->valueToString();
        }
    }
    
    
    public function getId(): string {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getPicture(): string{
        return $this->picture;
    }

    public function getAttributes(): Array {
        return $this->attributes;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function getCreationDate(): string {
        return $this->creationDate;
    }

    public function getLastUpdateDate(): string {
        return $this->lastUpdateDate;
    }


}
