<?php

namespace App\Article\Application\Response;

use App\Article\Application\Poco\ParkingPOCO;

/**
 * This is the application response to the controller
 *
 * @author mochiwa
 */
class ArticleApplicationResponse extends AbstractApplicationResponse{
    /**
     * @var ParkingPOCO 
     */
    private $article;
    
    protected function __construct(ParkingPOCO $article,array $errors) {
        parent::__construct($errors);
        $this->article=$article;
    }
    
    public static function of(ParkingPOCO $article=null,$errors=[]): self{
        return new self($article ?? ParkingPOCO::empty(),$errors);
    }
    
    public function getArticle(): ParkingPOCO {
        return $this->article;
    }

    public function withArticle(ParkingPOCO $article):self {
        $this->article = $article;
        return $this;
    }


    
    
}
