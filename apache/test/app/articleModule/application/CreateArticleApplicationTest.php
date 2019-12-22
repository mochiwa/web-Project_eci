<?php

use App\Article\Application\CreateArticleApplication;
use App\Article\Application\Request\CreateArticleRequest;
use App\Article\Model\Article\Article;
use App\Article\Model\Article\Service\CreateArticleService;
use Framework\FileManager\FileUploader;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;
use Framework\Validator\ValidatorException;
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
    private $domainService;
    
    private $app;
    private $request;
    protected function setUp() {
        $this->request= CreateArticleRequest::fromPostRequest(['title'=>'teste','picute'=>'teste','city'=>'city','place'=>'4','name'=>'teste','description'=>'teste']);
        $this->validator=$this->createMock(AbstractFormValidator::class);
        $this->domainService=$this->createMock(CreateArticleService::class);
        $this->uploader=$this->createMock(FileUploader::class);
        $this->session=$this->createMock(SessionManager::class);
        $this->app=new CreateArticleApplication($this->validator,$this->domainService,$this->uploader,$this->session);
    }

    
    function test_invoke_shouldReturnResponseWithErrors_whenValidatorIsNotValid()
    {
        $this->validator->expects($this->once())->method('validateOrThrow')->willThrowException(new ValidatorException(['anError']));
        
        $response=call_user_func($this->app,$this->request);
        $this->assertTrue($response->hasErrors());
        $this->assertEquals(['anError'],$response->getErrors());
    }
    
    function test_invoke_shouldExecuteDomainService_WhenValidatorHasNoError(){
        $this->validator->expects($this->once())->method('validateOrThrow')->willReturn(true);
        $this->domainService->expects($this->once())->method('__invoke');
        
        $response=call_user_func($this->app,$this->request);
        $this->assertFalse($response->hasErrors());
    }
    
    function test_invoke_shouldMoveThePictureFromTmpPlaceToDomainPlace_whenDomainHasBeenExucutedWithoutError(){
        $article=$this->createMock(Article::class);
        $this->validator->expects($this->once())->method('validateOrThrow')->willReturn(true);
        $this->domainService->expects($this->once())->method('__invoke')->willReturn($article);
        $this->uploader->expects($this->once())->method('uploadToDefault');
        call_user_func($this->app,$this->request);
    }
    
    function test_invoke_shouldInsertFlashMessage_whenCreationOfArticleSucess(){
        $article=$this->createMock(Article::class);
        $article->expects($this->any())->method('title')->willReturn(App\Article\Model\Article\Title::of('xxx'));
        $this->validator->expects($this->once())->method('validateOrThrow')->willReturn(true);
        $this->domainService->expects($this->once())->method('__invoke')->willReturn($article);
        $this->uploader->expects($this->once())->method('uploadToDefault');
        $this->session->expects($this->once())->method('setFlash')->with(Framework\Session\FlashMessage::success('The Article xxx has been created successfully'));
        $response=call_user_func($this->app,$this->request);
    }
   
    
}
