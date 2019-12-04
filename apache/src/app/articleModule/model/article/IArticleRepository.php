<?php


namespace App\Article\Model\Article;

use Framework\Paginator\IPaginable;


/**
 * The interface for article repository
 *
 * @author mochiwa
 */
interface IArticleRepository extends IPaginable {
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
     * @param ArticleId $id
     * @return void
     */
    public function removeArticle(ArticleId $id):void ;
    
    /**
     * Return the article that title match
     * @param Title $articleTitle
     * @return Article
     */
    public function findByTitle(Title $articleTitle): Article;

    /**
     * Update data of an article from the repository
     * @param Article $article
     * @return void
     */
    public function update(Article $article): void;
}
