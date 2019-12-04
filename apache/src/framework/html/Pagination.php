<?php

namespace Framework\Html;

use Framework\Html\Factory\AbstractPaginationFactory;
use Framework\Html\Factory\DefaultPaginationFactory;
use Framework\Router\IRouter;

/**
 * Description of Pagination
 *
 * @author mochiwa
 */
class Pagination extends HtmlTag{
    private $factory;
    /**
     * contain links built by the factory (@see Link)
     * @var array 
     */
    private $links;


    /**
     * Link to next page
     * @var HtmlTag 
     */
    private $toNext;
    
    /**
     * the current page number
     * @var int
     */
    private $currentPage;
    /**
     * the count of page can be generate
     * @var int 
     */
    private $pageCount=1;
    
    /**
     * Set the max page to generate
     * @var int 
     */
    private $pageCountLimite=10;
    
    public function __construct(AbstractPaginationFactory $factory) {
        parent::__construct('div');
        
     
        $this->factory=$factory;
        
        $this->toNext=$factory->toNext('#');
        $this->links=[];
    }
    
    public function toHtml(): string {
        $this->addChild($this->buildToPrevious());
        $this->generateLink();
        array_map([$this,'parent::addChild'], $this->links);
        $this->addChild($this->buildToNext());
        return parent::toHtml();
    }
    
    private function buildToPrevious(): HtmlTag
    {
        if(!isset($this->currentPage) || $this->currentPage===1){
           return $this->factory->toPrevious('#');
        }
        return $this->factory->toPrevious ($this->currentPage-1);
    }
    private function buildToNext(): HtmlTag
    {
        if(!isset($this->currentPage) || $this->currentPage===$this->pageCount){
           return $this->factory->toNext('#');
        }
        return $this->factory->toNext ($this->currentPage+1);
    }
    
    
    /**
     * Define the current page
     * @param int the page number
     * @return \self
     */
    public function setCurrentPage(int $page):self
    {
        $this->currentPage=$page;
        return $this;
    }
    
    /**
     * Define the total page count that
     * can be generated
     * @param int $pageCount
     * @return \self
     */
    public function setPageCount(int $pageCount):self
    {
        $this->pageCount=$pageCount;
        return $this;
    }
    
    /**
     * Set the max limit page link to generate
     * @param int $pageLimite
     * @return \self
     */
    public function setPageLimite(int $pageLimite):self
    {
        $this->pageCountLimite=$pageLimite;
        return $this;
    }
    
    
    public function generateLink()
    {
     
    }
    
    public function getLocalLimit($iterator=0,$const=0)
    {
        if($this->pageCount===1 || $iterator*$const > $this->pageCountLimite)
            return $this->pageCount;
        elseif($iterator*$const > $this->currentPage)
            return $iterator*$const ;
        else
            return $this->getLocalLimit($iterator+1,$this->pageCountLimite);
        
       
    }
    
    

    
    
}
