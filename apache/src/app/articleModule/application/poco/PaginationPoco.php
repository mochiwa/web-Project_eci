<?php

namespace App\Article\Application\Poco;

/**
 * Description of PaginationPoco
 *
 * @author mochiwa
 */
class PaginationPoco {
    /**
     * link to the current page
     * @var string
     */
    private $currentPage;
    /**
     * set of page's link 
     * @var array 
     */
    private $setOfPage;
    /**
     * the link of the previous page
     * @var string 
     */
    private $previousPage;
    /**
     * the link of next page
     * @var string 
     */
    private $nextPage;
    
    
    private function __construct(string $currentPage,array $setOfPage,string $previousPage,string $nextPage) {
        $this->currentPage = $currentPage;
        $this->setOfPage = $setOfPage;
        $this->previousPage = $previousPage;
        $this->nextPage = $nextPage;
    }
    
    
    public static function of(string $currentPage,array $setOfPage,string $previousPage,string $nextPage):self{
        return new self($currentPage,$setOfPage,$previousPage,$nextPage);
    }
    
 
    
    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function getSetOfPage() {
        return $this->setOfPage;
    }

    public function getPreviousPage() {
        return $this->previousPage;
    }

    public function getNextPage() {
        return $this->nextPage;
    }



    
    
}
