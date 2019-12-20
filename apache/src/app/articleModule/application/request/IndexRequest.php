<?php
namespace App\Article\Application\Request;
/**
 * This request is used by the index application service
 *
 * @author mochiwa
 */
class IndexRequest {
    private $articlePerPage;
    private $currentPage;
    private $indexURL;
    

    private function __construct( $articlePerPage, $currentPage,$indexUrl) {
        $this->articlePerPage = $articlePerPage;
        $this->currentPage = $currentPage;
        $this->indexURL=$indexUrl;
    }
    
    public static function of( $articlePerPage, $currentPage,string $url):self{
        return new self($articlePerPage,$currentPage,$url);
    }

    
    public function getArticlePerPage() {
        return $this->articlePerPage;
    }

    public function getCurrentPage(){
        return $this->currentPage;
    }
    
    public function getIndexURL() {
        return $this->indexURL;
    }


    


    
}
