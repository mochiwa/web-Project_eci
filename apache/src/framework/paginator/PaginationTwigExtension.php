<?php

namespace Framework\Paginator;

use Framework\Router\IRouter;
use Twig\Extension\AbstractExtension;

/**
 * Description of PaginationTwigExtension
 *
 * @author mochiwa
 */
class PaginationTwigExtension extends AbstractExtension{

    
    /**
     * Instance of router from this framework
     * @var IRouter 
     */
    protected $router;
    
   
    public function __construct(IRouter $router)
    {
        $this->router=$router;
    }

 
    
    public function getFunctions() {
        return [
            new \Twig\TwigFunction('pages',  [$this,'pages']),
            new \Twig\TwigFunction('previousLink',  [$this,'previousLink']),
            new \Twig\TwigFunction('pageLink',  [$this,'pageLink']),
            new \Twig\TwigFunction('nextLink',  [$this,'nextLink']),
        ];
    }

    public function pages(Pagination $pagination){
        return $pagination->getLinks();
    }
    
    public function previousLink(Pagination $pagination,string $urlName,string $action,string $slug)
    {
        return $this->router->generateURL($urlName,['action'=>$action,$slug=>$pagination->getPrevious()]);
    }
    
    public function pageLink(Pagination $pagination,string $urlName,string $action,string $slug)
    {
        return $this->router->generateURL($urlName,['action'=>$action,$slug=>$pagination->getCurrentPage()]);
    }
    
    public function nextLink(Pagination $pagination,string $urlName,string $action,string $slug)
    {
        return $this->router->generateURL($urlName,['action'=>$action,$slug=>$pagination->getNext()]);
    }
    
  
    
    
   

    
}
