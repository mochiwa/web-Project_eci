<?php

namespace App\Article\Model\Article\Service\Request;

use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Title;

/**
 * Description of EditArticleRequest
 *
 * @author mochiwa
 */
class EditArticleRequest {
    /**
     *
     * @var ArticleId the article Id 
     */
    private $articleId;
    
    /**
     *
     * @var Title the article title
     */
    private $title;
    /**
     *
     * @var Picture the picture path
     */
    private $picture;
    /**
     *
     * @var array that contain Attribute 
     */
    private $attributes;
    /**
     *
     * @var string the article description
     */
    private $description;
    
    private function __construct(ArticleId $articleId, Title $title, Picture $picture,array $attributes, string $description) {
        $this->articleId = $articleId;
        $this->title = $title;
        $this->picture = $picture;
        $this->attributes = $attributes;
        $this->description = $description;
    }

    
    public static function of(ArticleId $articleId, Title $title, Picture $picture,array $attributes, string $description) : self
    {
        return new self($articleId,$title,$picture,$attributes,$description);
    }
 
    
    public function getArticleId() : ArticleId {
        return $this->articleId;
    }

    public function getTitle() : Title {
        return $this->title;
    }

    public function getPicture() : Picture {
        return $this->picture;
    }

    public function getAttributes() : array {
        return $this->attributes;
    }

    public function getDescription() :string {
        return $this->description;
    }


    
}
