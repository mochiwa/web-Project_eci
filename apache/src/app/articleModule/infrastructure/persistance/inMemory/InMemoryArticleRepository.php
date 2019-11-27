<?php
namespace App\Article\Infrastructure\Persistance\InMemory;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Title;

/**
 * Description of InMemoryArticleRepository
 *
 * @author mochiwa
 */
class InMemoryArticleRepository extends AbstractInMemoryFactory implements IArticleRepository {
    //private $articles=[];
    
    public function __construct() {
        parent::__construct('Article');
        $this->load();
       // $this->articles= unserialize(file_get_contents( 'data.txt' ));
        
    }
    
    /**
     * Return a random ArticleId
     * @return ArticleId
     */
    public function nextId(): ArticleId {
        return ArticleId::of(uniqid());
    }

    /**
     * Find an article by its Id 
     * @param ArticleId $id
     * @return Article
     * @throws EntityNotFoundException when any article found
     */
    public function findById(ArticleId $id): Article {
        foreach ($this->data as $article)
        {
            if($article->id()==$id)
                return $article;
        }
        throw new EntityNotFoundException("The Article with id=".$id->idToString()." not found in repository");
    }

    /**
     * Append an article to the repository
     * @param Article $article
     */
    public function addArticle(Article $article) {
        $this->data[]=$article;
         $this->commit();//file_put_contents('data.txt', serialize($this->articles) );
    }

    /**
     * Return all article
     * @return array
     */
    public function all(): array {
        return $this->data;
    }
    
    /**
     * 
     * @param Title $title
     * @return bool
     */
    public function isArticleTitleExist(Title $title):bool
    {
        foreach ($this->data as $key) {
            if($key->title()==$title){
                return true;
            }
        }
        return false;
    }

}