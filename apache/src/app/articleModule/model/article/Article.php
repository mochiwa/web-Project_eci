<?php
namespace App\Article\Model\Article;

/**
 * This class is a representation of an article
 *
 * @author mochiwa
 */
class Article {
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
    /**
     * Build a new article where the creation date and last update will be init
     * By the system
     * @param ArticleId $id 
     * @param Title $title
     * @param Picture $picture
     * @param array $attributes
     * @param string $description
     * @return \self
     */
    public static function newArticle( ArticleId $id, Title $title,
            Picture $picture,array $attributes, string $description)
    {
        $currentTime=Date::fromTimeStamp(time());
        return new self($id,$title,$picture,$attributes,$description, $currentTime,$currentTime);
        
    }
    
    /**
     * Create an article with a specified date creation but where
     * the lastUpdateTime is updated to system now
     * @param ArticleId $id
     * @param Title $title
     * @param Picture $picture
     * @param array $attributes
     * @param string $description
     * @param Date $creationDate
     * @return \self
     */
    public static function fromUpdate( ArticleId $id, Title $title,
            Picture $picture,array $attributes, string $description,Date $creationDate)
    {
        $updateTime=Date::fromTimeStamp(time());
        return new self($id,$title,$picture,$attributes,$description, $creationDate,$updateTime);
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
