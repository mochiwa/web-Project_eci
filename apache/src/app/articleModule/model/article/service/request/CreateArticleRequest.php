<?php
namespace App\Article\Model\Article\Service\Request;


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
        $this->title=$title;
        $this->picture=$picture;
        foreach ($attributes as $key=>$value) {
            $this->attributes[$key]= $value;
        }
        $this->description=$description;
    }
    
    public static function fromArray(array $postData)
    {
        return new self($postData['title'],
                $postData['picture'],
                ['city'=>$postData['city'],'name'=>$postData['name']],
                $postData['description']);
    }
    
    /**
     * 
     * @return string
     */
    function getTitle() : string {
        return $this->title;
    }
    
    /**
     * 
     * @return string
     */
    function getPicture() : string{
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
