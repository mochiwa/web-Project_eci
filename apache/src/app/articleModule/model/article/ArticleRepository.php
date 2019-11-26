<?php


namespace App\Article\Model\Article;

/**
 * Description of ArticleRepository
 *
 * @author mochiwa
 */
interface ArticleRepository {
    public function nextId(): ArticleId;
}
