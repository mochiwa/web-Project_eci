<?php
namespace App\Article\Infrastructure\Persistance\InMemory;

use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\ArticleRepository;

/**
 * Description of InMemoryArticleRepository
 *
 * @author mochiwa
 */
class InMemoryArticleRepository implements ArticleRepository {
    //put your code here
    public function nextId(): ArticleId {
        return ArticleId::of(uniqid());
    }

}
