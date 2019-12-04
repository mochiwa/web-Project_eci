<?php
namespace App\Html;

use Framework\Html\Link;
/**
 * Description of LinkFactory
 *
 * @author mochiwa
 */
class LinkFactory {
    public static function topNavLink(string $href,string $text,bool $isLast=false)
    {
        $a=new Link($href,$text);
        $a->addStyle('nav__item');
        if(strpos($_SERVER['REQUEST_URI'], strtolower($text)))
        {
            $a->addStyle ('nav__item--selected');
        }
        if($isLast)
        {
            $a->addStyle('nav__item--last');
        }
        return $a;
        
    }
}
