<?php

namespace App\Article\Model\Article\Service\Finder;

use App\Article\Model\Article\IArticleRepository;

/**
 * Description of IFinder
 *
 * @author mochiwa
 */
interface IFinder {
    function setSearchValue($value) : self;
    
    function __invoke(IArticleRepository $repository) : array;
}
