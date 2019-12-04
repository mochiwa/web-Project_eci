<?php

namespace Framework\Html\Factory;

use Framework\Html\HtmlTag;
use Framework\Html\Link;
use Framework\Router\IRouter;

/**
 * Description of DefaultPaginationFactory
 *
 * @author mochiwa
 */
class DefaultPaginationFactory extends AbstractPaginationFactory {
    private $router;
    
    public function __construct(IRouter $router)
    {
        $this->router=$router;
    }
    
    public function toPrevious(string $link):link {
        return new Link($this->generateLink($link),'<<');
    }

    public function toNext(string $link): link {
        return new Link($this->generateLink($link),'>>');
    }
    public function page(int $pageNumber): link {
        return new Link($this->generateLink($pageNumber),$pageNumber);
    }
    protected function generateLink($link): string {
        return $this->router->generateURL($link);
    }

    public function currentPage(int $number): HtmlTag {
        return HtmlTag::make('p')->addText($number);
    }

}
