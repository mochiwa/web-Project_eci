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
     * @return ArticleId
     */
    public function nextId(): ArticleId;
    
    /**
     * Return the article that id match
     * @return Article
     */
    public function findById(ArticleId $id): Article;
    
    /**
     * Append an article to the repository
     * @param Article $article
     */
    public function addArticle(Article $article);
    
    /**
     * Return all contain from the repository
     * @return array
     */
    public function all(): array;
    
    /**
     * Return true if an article with this title already exist
     * @param Title $title
     * @return bool
     */
    public function isArticleTitleExist(Title $title):bool;
    
    /**
     * Return true if an article with this id exist
     * @param Title $title
     * @return bool
     */
    public function isArticleIdExist(ArticleId $id):bool;
    
    /**
     * Remove an article from the repository
     * @param \App\Article\Model\Article\ArticleId $id
     * @return void
     */
    public function removeArticle(ArticleId $id):void ;

}
