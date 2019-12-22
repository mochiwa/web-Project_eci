<?php

namespace App\Article\Application\Request;

/**
 * Description of ArticleRequest
 *
 * @author mochiwa
 */
class ArticleRequest {
    /**
     * @var string 
     */
    private $articleId;
    
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $pictureTmpPath;
    /**
     * @var string
     */
    private $city;
    /**
     * @var string
     */
    private $place;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;
    
    public function __construct(string $articleId, string $title,string  $pictureTmpPath,string  $city,string  $place,string  $name,string $description) {
        $this->articleId=$articleId;
        $this->title = $title;
        $this->pictureTmpPath = $pictureTmpPath;
        $this->city = $city;
        $this->place = $place;
        $this->name = $name;
        $this->description = $description;
    }
    
    public static function fromPostRequest(array $post,string $id=''){
        return new self(
            $id ,
            $post['title'] ?? '',
            $post['picture'] ?? '',
            $post['city'] ?? '',
            $post['place'] ?? '',
            $post['name'] ?? '',
            $post['description'] ?? '');
    }
    
    
    
    public function toAssociativeArray(){
        return [
            'id' =>$this->getArticleId(),
            'title'=>$this->getTitle(),
            'picture'=>$this->getPictureTmpPath(),
            'city'=>$this->getCity(),
            'place'=>$this->getPlace(),
            'name'=>$this->getName(),
            'description'=>$this->getDescription()
        ];
    }
    
    public function getArticleId():string{
        return $this->articleId;
    }
    
    public function getTitle() : string  {
        return $this->title;
    }

    public function getPictureTmpPath() : string  {
        return $this->pictureTmpPath;
    }

    public function getCity() : string {
        return $this->city;
    }

    public function getPlace() : string {
        return $this->place;
    }

    public function getName() : string  {
        return $this->name;
    }

    public function getDescription(): string  {
        return $this->description;
    }
}
