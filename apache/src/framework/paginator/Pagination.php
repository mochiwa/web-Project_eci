<?php
declare(strict_types=1);
namespace Framework\Paginator;
/**
 * Description of Pagination
 *
 * @author mochiwa
 */
class Pagination {
    /**
     * The current number
     * @var int
     */
    private $current;
    
    /**
     * The total page count
     * @var int 
     */
    private $pageCount;
    
    /**
     * The count of page to generate
     * @var int
     */
    private $limitToGenerate;


    public function __construct(int $pageCount=1) {
        $this->setTotalPageCount($pageCount);
        $this->setLimitToGenerate(1);
    }
    
    /**
     * Return the previous page about the current,
     * If the current is less or equals 1 return 1
     * @return int
     */
    public function getPrevious():int
    {
        return $this->current<=1 ?  1 :  $this->current-1;
    }
    /**
     * Return the next page about the current,
     * If the current is equals 1 then return 1,
     * else if the page count and current are equals return current
     * @return int
     */
    public function getNext():int
    {
        return $this->isLastPageReach() ? $this->current : $this->current+1;
    }
    /**
     * Return true if the current page equals the total page count
     * Than means current page is the last page.
     * @return bool
     */
    public function isLastPageReach() : bool
    {
       return $this->pageCount===$this->current ;
    }
    /**
     * Return true if the current page is the last of the current set
     * @return bool
     */
    private function isLastPageOfSetReach():bool{
        return $this->current===$this->limitToGenerate;
    }
    
    public function  getLinks():array
    {
        $links=[];
        
        if($this->isLastPageOfSetReach())
        {
            $links=$this->generateNewSetOfLinks($this->current);
        }
        else
        {
            $max=0;
            $current = isset($this->current) ? $this->current : 1;
            for($i=0;$max<=$current ; $i++)
            {
                $max=$this->limitToGenerate*$i;
            }
            $min=$max - $this->limitToGenerate;
            
            $min= $min<=0 ? 1 : $min;
            $max = $max <= 0 ? 1:$max+1;
      
            if($max >= $this->pageCount)
                $min=$max - ($this->limitToGenerate*2);
            
            $links=$this->generateNewSetOfLinks($min);
           /* $maxPageAvailable= $this->pageCount;
            $setSize=$this->limitToGenerate;
            
            for ($page = 0; $page < $maxPageAvailable && $page < $setSize ;  ++$page) {
                $links[$page] = $min + $page;
            }*/
           /* for($i=$min;$i!=$this->pageCount+1 && $i<=$max && sizeof($links)<$this->limitToGenerate;++$i)
            {
                $links[]=$i;
            }*/
            
           
        }
        return $links;
    }
    
    private function generateNewSetOfLinks(int $actual) {
        $maxPageAvailable= $this->pageCount;
        $setSize=$this->limitToGenerate;
        $links=[];
        for ($page = 0; $page < $maxPageAvailable && $page < $setSize ;  ++$page) {
            $links[$page] = $actual + $page;
        }
        return $links;
    }

    public function setCurrent(int $current)
    {
        $this->current=$current;
    }
    
    public function setTotalPageCount(int $pageCount)
    {
        $this->pageCount = $pageCount <= 0 ? 1 : $pageCount;
    }
    public function setLimitToGenerate(int $limit)
    {
        $this->limitToGenerate= $limit <= 0 ? 1 : $limit;
    }
    public function getLimitToGenerate()
    {
        return $this->limitToGenerate;
    }
    
    public function getPageCount()
    {
        return $this->pageCount;
    }
}
