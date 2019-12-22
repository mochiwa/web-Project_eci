<?php
namespace App\Article\Model\Article\Service\Request;

use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Title;


/**
 * The input DTO responsible to convert
 * base type to the model value object
 * for the service CreateArticleService
 *
 * @author mochiwa
 */
class CreateArticleRequest {
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
     * that contain array of Attribute
     * @var array
     */
    private $attributes;
    /**
     *
     * @var string the article description
     */
    private $description;
    
    
    private function __construct(Title $title ,Picture $picture,Array $attributes,string $description) {
        $this->title=$title;
        $this->picture=$picture;
        $this->attributes=$attributes;
        $this->description=$description;
    }
    
    public static function of(Title $title ,Picture $picture,Array $attributes,string $description):self{
        return new self($title,$picture,$attributes,$description);
    }
    /**
     * 
     * @return string
     */
    function getTitle() : Title {
        return $this->title;
    }
    
    /**
     * 
     * @return string
     */
    function getPicture() : Picture{
        return $this->picture;
    }
    /**
     * 
     * @return array of attribute
     */
    function getAttributes() : array{
        return $this->attributes;
    }
    /**
     * 
     * @return string
     */
    function getDescription() : string{
        return $this->description;
    }


    
    
}
