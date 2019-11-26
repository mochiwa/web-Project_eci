<?php
namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Title;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\Picture;

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
    
    function __construct(string $title ,string $picture,Array $attributes,string $description) {
        $this->title=Title::of($title);
        $this->picture=Picture::of($picture);
        foreach ($attributes as $key=>$value) {
            $this->attributes[]= Attribute::of($key,$value);
        }
        $this->description=$description;
    }
    
    /**
     * 
     * @return Title
     */
    function getTitle() : Title {
        return $this->title;
    }
    
    /**
     * 
     * @return Picture
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
