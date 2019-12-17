<?php

namespace App\Article\Application\Service\Response;

use App\Article\Application\Service\Dto\ParkingView;

/**
 * Description of ShowArticleResponse
 *
 * @author mochiwa
 */
class ShowArticleResponse {
    
    /**
     * @var array 
     */
    private $errors;
    
    /**
     * @var ParkingView 
     */
    private $article;
    
    
    
    private function __construct() {
        $this->errors=[];
        $this->article= ParkingView::empty();
    }
    
    public static function of():self{
        return new self();
    }
    
    public function withErrors(array $error) : self
    {
        $this->errors=$error;
        return $this;
    }
    public function withArticle(ParkingView $article):self{
        $this->article=$article;
        return $this;
    }
    
    public function hasErrors():bool{
        return !empty($this->errors);
    }
    
    public function getArticle() : ParkingView{
        return $this->article;
    }
}
