<?php

namespace App\Article\Application\Service\Dto;

use App\Article\Model\Article\Service\Response\ArticleDomainResponse;

/**
 * Description of ParkingView
 *
 * @author mochiwa
 */
class ParkingView {

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

    public function __construct($id, $title, $picture, Array $attributes, $description, $creationDate, $lastUpdateDate) {
        $this->id = $id;
        $this->title = $title;
        $this->picture = $picture;
        $this->attributes = $attributes;
        $this->description = $description;
        $this->creationDate = $creationDate;
        $this->lastUpdateDate = $lastUpdateDate;
    }

    public static function fromDomainResponse(ArticleDomainResponse $article): self {
        return new self(
                $article->id()->idToString(),
                $article->title()->valueToString(),
                $article->picture()->path(),
                ['city'=>$article->attributes()['city']->valueToString(),
                    'place'=>$article->attributes()['place']->valueToString(),
                    'name'=>$article->attributes()['name']->valueToString()],
                $article->description(),
                $article->creationDate()->toHumainReadable(),
                $article->lastUpdateDate()->toHumainReadable()
        );
    }
    
    public static function fromPost(array $post) :self {
        return new self(
                $post['id'] ?? '',
                $post['title'] ?? '',
                $post['picture'] ?? '',
                ['city'=>$post['city']?? '','place'=>$post['place'] ?? '','name'=>$post['name']?? ''],
                $post['description'] ?? '',
                 $post['creationDate'] ?? '' , $post['lastUpdateDate'] ?? '');
    }
    
    public function getId() :string {
        return htmlentities($this->id);
    }

    public function getTitle():string {
        return htmlentities($this->title);
    }

    public function getPicture():string {
        return htmlentities($this->picture);
    }

    public function getAttributes(): Array {
        return array_map('htmlentities',$this->attributes);
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
    
    public function toForm():array
    {
        return [
            'form-title'=>$this->getTitle(),
            'form-picture'=>$this->getPicture(),
            'form-city'=>$this->getAttributes()['city'],
            'form-place'=>$this->getAttributes()['place'],
            'form-name'=>$this->getAttributes()['name'],
            'form-description'=>$this->getDescription()
        ];
    }

}
