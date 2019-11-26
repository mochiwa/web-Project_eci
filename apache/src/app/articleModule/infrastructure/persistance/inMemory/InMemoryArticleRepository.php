<?php
namespace App\Article\Infrastructure\Persistance\InMemory;

use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\IArticleRepository;

/**
 * Description of InMemoryArticleRepository
 *
 * @author mochiwa
 */
class InMemoryArticleRepository implements IArticleRepository {
    private $articles=[];
    public function nextId(): ArticleId {
        return ArticleId::of(uniqid());
    }

    public function findById(ArticleId $id): Article {
        foreach ($this->articles as $article)
        {
            if($article->id()==$id)
                return $article;
        }
        throw new EntityNotFoundException("The Article with id=".$id->idToString()." not found in repository");
    }

    public function addArticle(Article $article) {
        $this->articles[]=$article;
    }

}