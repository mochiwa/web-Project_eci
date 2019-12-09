<?php

use App\Article\Application\Service\CreateArticleApplication;
use App\Article\Application\Service\DeleteArticleApplication;
use App\Article\Application\Service\EditArticleApplication;
use App\Article\Application\Service\IndexArticleApplication;
use App\Article\Infrastructure\Persistance\InMemory\InMemoryArticleRepository;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\ArticleFinder;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\DeleteArticleService;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Validation\ParkingEditFormValidator;
use App\Article\Validation\ParkingFormValidator;
use Framework\FileManager\FileUploader;
use Framework\FileManager\PostUploader;
use Framework\Session\ISession;
use Framework\Session\PhpSession;
use Framework\Session\SessionManager;

return [
    IArticleRepository::class => function(){return new InMemoryArticleRepository();},
    FileUploader::class => function (){return new FileUploader(new PostUploader(), getcwd().DIRECTORY_SEPARATOR.'upload/article');},
    IndexArticleApplication::class => function($di){return new IndexArticleApplication($di->get(IArticleRepository::class));},
            
    CreateArticleApplication::class => function($di){
        return new CreateArticleApplication(
                $di->get(CreateArticleService::class),
                $di->get(ParkingFormValidator::class),
                $di->get(FileUploader::class),
                $di->get(SessionManager::class));},
                        
    EditArticleApplication::class => function($di){
        return new EditArticleApplication(
            $di->get(ArticleFinder::class),
            $di->get(EditArticleService::class),
            $di->get(ParkingEditFormValidator::class),
            $di->get(SessionManager::class));},
                    
    DeleteArticleApplication::class => function($di){
        return new DeleteArticleApplication(
            $di->get(DeleteArticleService::class),
            $di->get(SessionManager::class));},
    

                
    
                
];