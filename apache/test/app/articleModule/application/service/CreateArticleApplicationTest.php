<?php

use App\Article\Application\Service\CreateArticleApplication;
use App\Article\Application\Service\Response\CreatedResponse;
use App\Article\Model\Article\Service\CreateArticleService;
use Framework\FileManager\FileUploader;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;
use PHPUnit\Framework\TestCase;

/**
 * Description of CreateArticleApplicationTest
 *
 * @author mochiwa
 */
class CreateArticleApplicationTest extends TestCase{
    private $validator;
    private $uploader;
    private $session;
    private $service;
    
    private $app;
    protected function setUp() {
        $this->validator=$this->createMock(AbstractFormValidator::class);
        $this->uploader=$this->createMock(FileUploader::class);
        $this->service=$this->createMock(CreateArticleService::class);
        $this->session=$this->createMock(SessionManager::class);
        $this->app=new CreateArticleApplication($this->service,$this->validator,$this->uploader,$this->session);
    }

}
