<?php

namespace App\Article\view\ViewFactory;

use Framework\Html\Factory\DefaultPaginationFactory;
use Framework\Html\HtmlTag;
use Framework\Html\Link;
use Framework\Router\IRouter;

/**
 * Description of PaginationFactory
 *
 * @author mochiwa
 */
class PaginationFactory extends DefaultPaginationFactory {
    private $baseUrl;
    private $slug;
    public function __construct(IRouter $router,string $baseUrl,string $slug='page') {
        parent::__construct($router);
        $this->baseUrl=$baseUrl;
        $this->slug=$slug;
    }

    
    
    protected function generateLink($link): string {
        return $this->router->generateURL($this->baseUrl,['action'=>'index',$this->slug => $link]);
    }
    
    public function currentPage(int $number): HtmlTag {
        return parent::currentPage($number)->addStyle("pagination__item")->addStyle('pagination__item--current');
    }

    public function page(int $pageNumber): Link {
        return parent::page($pageNumber)->addStyle("pagination__item");
    }

    public function toNext(string $link): Link {
        return parent::toNext($link)->addStyle("pagination__item");
    }

    public function toPrevious(string $link): Link{
        return parent::toPrevious($link)->addStyle("pagination__item");
    }

    public function mainStyle(): string {
        return "pagination";
    }


}
