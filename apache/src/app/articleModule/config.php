<?php

use App\Article\Infrastructure\Persistance\InMemory\InMemoryArticleRepository;
use App\Article\Model\Article\IArticleRepository;
return [
    IArticleRepository::class => function(){return new InMemoryArticleRepository();}
];