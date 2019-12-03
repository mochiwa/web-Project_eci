<?php
namespace App\Article\Application\Service\Dto;

use App\Article\Model\Article\Service\Response\ArticleDomainResponse;

/**
 * This class is responsible to take an article
 * and transform it to match with an array that 
 * a form can deals with.
 *
 * @author mochiwa
 */
class ArticleToForm {
    private $data;
    private $id;
    public function __construct(string $id,array $data) {
        $this->id=$id;
        $this->data=$data;
    }
   
    public static function fromArray(array $array)
    {
        $id = isset($array['id']) ?? '';  
        return new self($id,[
            'form-title'=>$array['title'],
            'form-picture'=>$array['picture'],
            'form-city'=>$array['city'],
            'form-place'=>$array['place'],
            'form-name'=>$array['name'],
            'form-description'=>$array['description']
        ]);
    }
    
    public static function fromArticleView(ArticleView $article)
    {
        return new self($article->getId(),[
            'form-title'=>$article->getTitle(),
            'form-picture'=>$article->getPicture(),
            'form-city'=>$article->getAttributes()['city']->valueToString(),
            'form-place'=>$article->getAttributes()['place']->valueToString(),
            'form-name'=>$article->getAttributes()['name']->valueToString(),
            'form-description'=>$article->getDescription()
        ]);
    }
    
    public static function fromDomainResponse(ArticleDomainResponse $article)
    {
        return self::fromArticleView(new ArticleView($article));
    }
    
    public function toForm():array
    {
        return $this->data;
    }
    
    public function getId()
    {
        return $this->id;
    }
}
