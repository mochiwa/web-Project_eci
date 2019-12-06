<?php

namespace App\Article\Application\Service\Response;

use App\Article\Application\Service\Dto\ParkingView;

/**
 * Description of CreateResponse
 *
 * @author mochiwa
 */
class CreatedResponse extends AbstractApplicationResponse{
    /**
     * The article view 
     * @var ParkingView 
     */
    private $article;
    
    
    public function __construct(ParkingView $article,array $errors = [] ) {
        parent::__construct($errors);
        $this->article=$article;
    }
    
    public static function of(ParkingView $article,array $errors = []) : self {
        return new self($article,$errors);
    }
    public static function success(ParkingView $article) : self {
        return new self($article);
    }
    
    public function getArticle(): ParkingView
    {
        return $this->article;
    }
    
    
}
