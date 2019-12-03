<?php

namespace App\Article\Application\Service\Dto;

use App\Article\Model\Article\Service\Response\ArticleDomainResponse;

/**
 * DTO for the view
 *
 * @author mochiwa
 */
class ArticleView {
    /**
     *
     * @var string the article id
     */
    private $id;
    /**
     *
     * @var string the article title
     */
    private $title;
    /**
     *
     * @var string picture of the article
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
     * @var string timestamp about the creation article 
     */
    private $creationDate;
    /**
     *
     * @var string timestamp for the last update 
     */
    private $lastUpdateDate;
    
    public function __construct(ArticleDomainResponse $article) {
        $this->id = $article->id()->idToString();
        $this->title = $article->title()->valueToString();
        $this->picture = $article->picture()->path();
        $this->attributes = $article->attributes();
        $this->description = $article->description();
        $this->creationDate = $article->creationDate()->toHumainReadable();
        $this->lastUpdateDate = $article->lastUpdateDate()->toHumainReadable();
    }
    
    public function getId() :string {
        return $this->id;
    }

    public function getTitle():string {
        return htmlentities($this->title);
    }

    public function getPicture():string {
        return htmlentities($this->picture);
    }

    public function getAttributes(): Array {
        return $this->attributes;
    }

    public function getDescription():string {
        return htmlentities($this->description);
    }

    public function getCreationDate():string {
        return htmlentities($this->creationDate);
    }

    public function getLastUpdateDate():string {
        return htmlentities($this->lastUpdateDate);
    }



}
