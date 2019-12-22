<?php

use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Request\ArticleRequest;
use App\Article\Application\UpdateArticleApplication;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\Service\EditArticleService;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;
use Framework\Validator\ValidatorException;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;

/**
 * Description of EditArticleApplicationTest
 *
 * @author mochiwa
 */
class UpdateArticleApplicationTest extends TestCase{
    private $app;
    private $validator;
    private $domainService;
    private $session;
    private $request;
    
    protected function setUp() {
        $this->validator=$this->createMock(AbstractFormValidator::class);
        $this->domainService=$this->createMock(EditArticleService::class);
        $this->session=$this->createMock(SessionManager::class);
        $this->app=new UpdateArticleApplication($this->validator,$this->domainService,$this->session);
        
        $this->request= ArticleRequest::fromPostRequest(['title'=>'aTitle','city'=>'aCity','place'=>'5','name'=>'aName','description'=>'desc'],'xxx');
    }
    
    
    function test_invoke_shouldReturnApplicationResponseWithError_whenValidatorThrowValidationException()
    {
        $request=$this->createMock(ArticleRequest::class);
        $request->expects($this->once())->method('toAssociativeArray')->willReturn([]);
        $this->validator->expects($this->once())->method('validateOrThrow')->willThrowException(new ValidatorException(['error'=>'anError']));
        $appResponse= call_user_func($this->app,$request);
        
        $this->assertTrue($appResponse->hasErrors());
        $this->assertEquals(['error'=>'anError'], $appResponse->getErrors());
    }
    
    function test_invoke_shouldReturnApplicationResponseWithArticlePOCOWithDataFrom_whenValidatorThrowException()
    {
        $request=$this->createMock(ArticleRequest::class);
        $request->expects($this->once())->method('toAssociativeArray')->willReturn(['title'=>'aTitle']);
        $this->validator->expects($this->once())->method('validateOrThrow')->willThrowException(new ValidatorException(['error'=>'anError']));
       
        $appResponse= call_user_func($this->app,$request);
        $this->assertEquals(ParkingPOCO::fromAssociativeArray(['title'=>'aTitle']), $appResponse->getArticle());
    }
    
    function test_invoke_shouldReturnApplicationResponseWithError_whenDomainServiceThrowArticleException(){
        $this->validator->expects($this->once())->method('validateOrThrow')->willReturn(true);
        $this->domainService->expects($this->once())->method('__invoke')->willThrowException(new ArticleException());
       
        $appResponse= call_user_func($this->app,$this->request);
        $this->assertTrue($appResponse->hasErrors());
    }
    
    function test_invoke_shouldAppendSuccessFlashMessage_whenArticleWasSuccessfulyUpdated()
    {
        $this->validator->expects($this->once())->method('validateOrThrow')->willReturn(true);
        $this->domainService->expects($this->once())->method('__invoke')->willReturn(TestHelper::get()->makeArticle('xxx','aTitle'));
        $this->session->expects($this->once())->method('setFlash')->with(FlashMessage::success('The article aTitle has been successfuly updated'));
       
        $appResponse= call_user_func($this->app,$this->request);
        $this->assertFalse($appResponse->hasErrors());
    }
    
    
}
