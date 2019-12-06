<?php

namespace App\Article\Application\Service\Response;

use App\Article\Application\Service\Dto\ParkingView;


/**
 * This response is returned by the EditArticleApplication service
 *
 * @author mochiwa
 */
class EditResponse extends AbstractApplicationResponse{
    /**
     * The view of the article
     * @var ParkingView 
     */
    private $article;
    
    /**
     * set if the article has been edited or not
     * @var bool 
     */
    private $isEdited;
    
    /**
     * set if the article not found 
     * @var bool 
     */
    private $isArticleNotFound;
    
    public function __construct($article=null,array $errors=[],bool $isEdited=false,bool $isArticleNotFound=false) {
        parent::__construct($errors);
        $this->article = $article;
        $this->isEdited=$isEdited;
        $this->isArticleNotFound=$isArticleNotFound;
    }
    
    /**
     * Return a new instance of this response
     * @param type $article
     * @param array $errors
     * @return \self
     */
    public static function of ($article=null,array $errors=[]):self
    {
        return new self($article,$errors);
    }
    
    /**
     * Must be returned when the article has been updated
     * without errors.
     * @param type $article
     * @param array $errors
     * @return \self
     */
    public static function edited($article):self
    {
        return new self($article,[],true);
    }
    
    /**
     * Must be used when the article is not found from domain
     * @return \self
     */
    public static function notFound(){
        return new self(null,[],false,true);
    }

  
    public function getArticle() : ParkingView {
        return $this->article;
    }
    
    public function isEdited():bool
    {
        return $this->isEdited;
    }

    public function isArticleNotFound():bool
    {
        return $this->isArticleNotFound;
    }
    
   
}
