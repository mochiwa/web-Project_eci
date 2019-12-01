<?php

namespace Framework\Paginator;

/**
 * Description of Paginator
 *
 * @author mochiwa
 */
class Paginator {
    /**
     * The link to a database or data structure
     * that implement IPaginable
     * @var IPaginable 
     */
    private $paginable;
    /**
     * Define the max count of data per page
     * @var Int 
     */
    private $maxDataPerPage;
    
    
    public function __construct(IPaginable $paginable,int $maxDataPerPage=5) {
        $this->paginable=$paginable;
        $this->maxDataPerPage=$maxDataPerPage;
    }
    
    
    /**
     * Define the max count of data per page
     * @param int $count
     */
    public function setMaxDataPerPage(int $count)
    {
        if($count<=0){
            throw new PaginatorException ('The max count of data per page cannot be equals or less than 0');
        }
        $this->maxDataPerPage=$count;
    }
    
    /**
     * Return the max data per page
     * @return int
     */
    public function maxDataPerPage():int
    {
        return $this->maxDataPerPage;
    }
    
    /**
     * Return the total number of page for this paginable,
     * If All contain can be contained into one page then return 1 (case where data count equals 0 )
     * any else return the value superior 
     * @return int
     */
    public function pageCount():int 
    {
        $page= $this->paginable->dataCount() / $this->maxDataPerPage;
        
        return $page === 0 ? 1 : ceil($page);
    }
    
    /**
     * Return a number of data for a specific page.
     * If the page is superior at page count or the page is equals or less than 0
     * return an empty array.
     * else if everything fine, return data from the paginable
     * @param int $page
     * @return array
     */
    public function getDataForPage(int $page):array
    {
        if($page > $this->pageCount() || $page <= 0){
            
            return [];
        }
        
        $current= $page===1 ? 0 : (($page-1) * $this->maxDataPerPage);
        return $this->paginable->getForPagination($current,$this->maxDataPerPage);
    }
}
