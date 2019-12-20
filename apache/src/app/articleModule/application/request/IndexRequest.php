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
    

    private function __construct( $articlePerPage, $currentPage) {
        $this->articlePerPage = $articlePerPage;
        $this->currentPage = $currentPage;
    }
    
    public static function of( $articlePerPage, $currentPage):self{
        return new self($articlePerPage,$currentPage);
    }

        
    public function getArticlePerPage() {
        return $this->articlePerPage;
    }

    public function getCurrentPage(){
        return $this->currentPage;
    }


    
}
