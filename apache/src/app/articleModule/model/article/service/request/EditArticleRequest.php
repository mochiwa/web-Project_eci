<?php

namespace App\Article\Model\Article\Service\Request;

/**
 * Description of EditArticleRequest
 *
 * @author mochiwa
 */
class EditArticleRequest {
    /**
     *
     * @var string the article Id 
     */
    private $articleId;
    
    /**
     *
     * @var string the article title
     */
    private $title;
    /**
     *
     * @var string the picture path
     */
    private $picture;
    /**
     *
     * @var array that contain key=>value in string format 
     */
    private $attributes;
    /**
     *
     * @var string the article description
     */
    private $description;
    
    function __construct(string $articleId,string $title ,Array $attributes,string $description,string $picture='') {
        $this->articleId=$articleId;
        $this->title=$title;
        $this->picture=$picture;
        foreach ($attributes as $key=>$value) {
            $this->attributes[$key]= $value;
        }
        $this->description=$description;
    }
    
    public static function fromArray(string $articleId,array $postData) : self
    {
        return new self($articleId,$postData['title'],
                ['city'=>$postData['city'],'name'=>$postData['name'],'place'=>$postData['place']],
                $postData['description']);
    }
 
    
    public function getArticleId() {
        return $this->articleId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function getDescription() {
        return $this->description;
    }


    
}
