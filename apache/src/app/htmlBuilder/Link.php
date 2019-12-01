<?php

namespace App\htmlBuilder;


/**
 * This class is responsible to generate all link for the JohnCar application,
 * It use the IRouter to generate target 			
 */
class Link extends \Framework\HtmlBuilder\Link {

    /**
     * Build the link with the router for the target
     * @param String $target target name used in the router
     * @param string $text   value between tag
     * @param array  $styles array list of styles 
     */
    function __construct(String $target, string $text, array $styles = []) {
        parent::__construct($target, $text,$styles);
    }

    /**
     * Generate a nav link
     * @param  String $target target name used in the router
     * @param  string $text   value between tag
     * @return self         
     */
    public static function navLink(String $target, string $text): self {
        $link = new self($target, $text);
        $link->addStyle('nav__item');
        return $link;
    }

    public static function topNavLink(String $target, string $text, bool $isLast = false): self {
        $link = Link::navLink($target, $text);
        $link->addStyle("nav__item--selected",$_SERVER['REQUEST_URI']===$target)
                ->addStyle("nav__item--last", $isLast);
        ;
        return $link;
    }

}
