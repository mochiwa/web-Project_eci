<?php

use App\Article\Application\CreateArticleApplication;
use App\Article\Application\Service\IndexArticleApplication;
use App\Article\Application\UpdateArticleApplication;
use App\Article\Infrastructure\Persistance\InMemory\InMemoryArticleRepository;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Validation\ParkingEditFormValidator;
use App\Article\Validation\ParkingFormValidator;
use Framework\DependencyInjection\IContainer;
use Framework\FileManager\FileUploader;
use Framework\FileManager\PostUploader;
use Framework\Paginator\PaginationTwigExtension;
use Framework\Renderer\IViewBuilder;
use Framework\Renderer\TwigFactory;
use Framework\Session\SessionManager;

return [
    'twig.extension' => [IContainer::ADD => [PaginationTwigExtension::class]] ,
    IViewBuilder::class => function ($di){return $di->get(TwigFactory::class)($di->get('twig.extension'));},
    
    IArticleRepository::class => function(){return new InMemoryArticleRepository();},
            
    FileUploader::class => function (){return new FileUploader(new PostUploader(), getcwd().DIRECTORY_SEPARATOR.'upload/article');},
    IndexArticleApplication::class => function($di){return new IndexArticleApplication($di->get(IArticleRepository::class));},
            
    CreateArticleApplication::class => function($di){
        return new CreateArticleApplication(
                $di->get(ParkingFormValidator::class),
                $di->get(CreateArticleService::class),
                $di->get(FileUploader::class),
                $di->get(SessionManager::class));},
    
    UpdateArticleApplication::class => function($di){
        return new UpdateArticleApplication(
            $di->get(ParkingEditFormValidator::class),
            $di->get(EditArticleService::class),
            $di->get(SessionManager::class));}                    
                        
   
                         
];