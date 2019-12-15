<?php
namespace App\Article\Infrastructure\Persistance\InMemory;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Title;
use App\Shared\Infrastructure\AbstractInMemoryRepository;

/**
 * Description of InMemoryArticleRepository
 *
 * @author mochiwa
 */
class InMemoryArticleRepository extends AbstractInMemoryRepository implements IArticleRepository {
    public function __construct() {
        parent::__construct('Article');
        $this->load();
        
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
        $this->data[$article->id()->idToString()]=$article;
         $this->commit();
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
    
    /**
     * 
     * @param ArticleId $id
     * @return bool
     */
    public function isArticleIdExist(ArticleId $id): bool {
        foreach ($this->data as $article) {
            if ($article->id() == $id){
                return true;
            }
        }
        return false;
    }

    /**
     * Remove article and commit changement
     * @param ArticleId $id
     * @return void
     * @throws EntityNotFoundException
     */
    public function removeArticle(ArticleId $id): void {
        if(!$this->isArticleIdExist($id))
            throw new EntityNotFoundException("Cannot remove the article with id=" . $id->idToString() . " not found in repository");
        unset($this->data[$id->idToString()]);
        $this->commit();
    }
    /**
     * Find article by its title
     * @param Title $articleTitle
     * @return Article
     * @throws EntityNotFoundException
     */
    public function findByTitle(Title $articleTitle): Article {
        foreach ($this->data as $article) {
            if($article->title()==$articleTitle)
                return $article;
        }
        throw new EntityNotFoundException("Any Article with title=".$articleTitle->valueToString()." found in repository");
    }

    /**
     * Update the article with id
     * @param Article $article
     * @return void
     */
    public function update(Article $article): void {
        if(!$this->isArticleIdExist($article->id())){
            throw new EntityNotFoundException('The Article with id='.$article->id()->idToString().'not updatable cause it is not found in reposity');
        }
        $this->data[$article->id()->idToString()]=$article;
        $this->commit();
    }
    /**
     * Return the size of data[]
     * @return int
     */
    public function dataCount(): int {
       return sizeof($this->data);
    }

    public function getForPagination(int $current, int $max): array {
        return array_slice($this->data, $current,$max);
    }

}