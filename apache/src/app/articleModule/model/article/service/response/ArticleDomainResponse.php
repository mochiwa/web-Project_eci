<?php

namespace App\Article\Model\Article\Service\Response;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Date;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Title;

/**
 * Description of ArticleDomainResponse
 *
 * @author mochiwa
 */
class ArticleDomainResponse {
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
    
    
    public function __construct(Article $article) 
    {
        $this->id = $article->id();
        $this->title = $article->title();
        $this->picture = $article->picture();
        $this->attributes = $article->attributes();
        $this->description = $article->description();
        $this->creationDate = $article->creationDate();
        $this->lastUpdateDate = $article->lastUpdateDate();
        
    }
    
    /**
     * Return the article Id
     * @return ArticleId
     */
    public function id() : ArticleId
    {
        return $this->id;
    }
    
    public function title() : Title
    {
        return $this->title;
    }
    
    
    public function picture(): Picture {
        return $this->picture;
    }

    public function attributes(): Array {
        return $this->attributes;
    }

    public function description() {
        return $this->description;
    }

    public function creationDate(): Date {
        return $this->creationDate;
    }

    public function lastUpdateDate(): Date {
        return $this->lastUpdateDate;
    }
}
