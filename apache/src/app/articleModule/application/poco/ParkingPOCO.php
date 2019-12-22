<?php
namespace App\Article\Application\Poco;


/**
 * This is as POCO (plain old CLR object) for a parking article
 *
 * @author mochiwa
 */
class ParkingPOCO {
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
    
    /**
     * Get an Parking and return a POCO
     * @param \App\Article\Model\Article\Article $article
     * @return \self
     */
    public static function of(\App\Article\Model\Article\Article $article): self {
        $attributes=[];
        foreach ($article->attributes() as $attribute) {
            $attributes[$attribute->keyToString()]=$attribute->valueToString();      
        }
        return new self(
                $article->id()->idToString(),
                $article->title()->valueToString(),
                $article->picture()->path(),
                $attributes,
                $article->description(),
                $article->creationDate()->toHumainReadable(),
                $article->lastUpdateDate()->toHumainReadable()
        );
    }
    
    public static function empty(): self {
        return new self('','','',[],'','','');
    }
    
    public static function fromAssociativeArray(Array $array):self{
        return new self(
            $array['id'] ?? '',
            $array['title'] ?? '',
            $array['picture'] ?? '',
            ['city'=>$array['city'] ?? '','place'=>$array['place'] ?? '','name'=>$array['name'] ?? ''],
            $array['description'] ?? '',
            $array['creationDate'] ?? '',
            $array['lastUpdateDate'] ?? ''
        );
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function getAttributes(): Array {
        return $this->attributes;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getLastUpdateDate() {
        return $this->lastUpdateDate;
    }


}
