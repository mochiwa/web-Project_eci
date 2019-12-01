<?php

namespace App\Article\Model\Article\Service\Response;

/**
 * Description of ArticleCreatedResponse
 *
 * @author mochiwa
 */
class ArticleCreatedResponse {
    private $picture;
    private $title;
    
    public function __construct(string $picture,string $title) {
        $this->picture = $picture;
        $this->title=$title;
    }
    
    public function getPicture() {
        return $this->picture;
    }
    
    public function getTitle() {
        return $this->title;
    }





    
}
