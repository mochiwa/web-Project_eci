<?php


namespace App\Article\Model\Article;

/**
 * The interface for article repository
 *
 * @author mochiwa
 */
interface IArticleRepository {
    /**
     * Return the next id available
     * @return \App\Article\Model\Article\ArticleId
     */
    public function nextId(): ArticleId;
    
    /**
     * Return the article that id match
     * @return \App\Article\Model\Article\Article
     */
    public function findById(ArticleId $id): Article;
    
    /**
     * Append an article to the repository
     * @param \App\Article\Model\Article\Article $article
     */
    public function addArticle(Article $article);
}
