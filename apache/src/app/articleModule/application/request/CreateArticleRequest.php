<?php

namespace App\Article\Application\Request;

/**
 * Description of CreateArticleRequest
 *
 * @author mochiwa
 */
class CreateArticleRequest {
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
    
    public function __construct(string $title,string  $pictureTmpPath,string  $city,string  $place,string  $name,string $description) {
        $this->title = $title;
        $this->pictureTmpPath = $pictureTmpPath;
        $this->city = $city;
        $this->place = $place;
        $this->name = $name;
        $this->description = $description;
    }
    
    public static function fromPostRequest(array $post){
        return new self(
            $post['title'] ?? '',
            $post['picture'] ?? '',
            $post['city'] ?? '',
            $post['place'] ?? '',
            $post['name'] ?? '',
            $post['description'] ?? '');
    }
    
    
    
    public function toAssociativeArray(){
        return [
            'title'=>$this->getTitle(),
            'picture'=>$this->getPictureTmpPath(),
            'city'=>$this->getCity(),
            'place'=>$this->getPlace(),
            'name'=>$this->getName(),
            'description'=>$this->getDescription()
        ];
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
