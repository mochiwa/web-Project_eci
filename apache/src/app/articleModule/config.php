<?php

use App\Article\Infrastructure\Persistance\InMemory\InMemoryArticleRepository;
use App\Article\Model\Article\IArticleRepository;
use Framework\FileManager\FileUploader;
use Framework\FileManager\PostUploader;
use Framework\Session\PhpSession;
use Framework\Session\SessionManager;
return [
    IArticleRepository::class => function(){return new InMemoryArticleRepository();},
    SessionManager::class => function(){return new SessionManager(new PhpSession());},
    FileUploader::class => function (){return new FileUploader(new PostUploader(), getcwd().DIRECTORY_SEPARATOR.'upload/article');}
];