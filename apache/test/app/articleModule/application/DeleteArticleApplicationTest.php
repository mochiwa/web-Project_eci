<?php

use App\Article\Application\DeleteArticleApplication;
use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Request\DeleteArticleRequest;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\Service\DeleteArticleService;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use PHPUnit\Framework\TestCase;
use Test\App\TestHelper;

/**
 * Description of DeleteArticleApplication
 *
 * @author mochiwa
 */
class DeleteArticleApplicationTest extends TestCase{
    private $app;
    private $domainService;
    private $sessionManager;
    protected function setUp() {
        $this->domainService=$this->createMock(DeleteArticleService::class);
        $this->sessionManager=$this->createMock(SessionManager::class);
        $this->app=new DeleteArticleApplication($this->domainService,$this->sessionManager);
    }
    
    public function test_invoke_shouldReturnApplicationResponseWithError_whenArticleIdIsEmpty(){
        $request=$this->createMock(DeleteArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('');
        
        $appResponse=call_user_func($this->app,$request);
        $this->assertTrue($appResponse->hasErrors());
    }
    
    public function test_invoke_shouldReturnApplicationResponseWithError_whenDomainServiceThrowArticleException(){
        $request=$this->createMock(DeleteArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('xxx');
        $this->domainService->expects($this->once())->method('__invoke')->willThrowException(new ArticleException('id'));
        
        $appResponse=call_user_func($this->app,$request);
        $this->assertTrue($appResponse->hasErrors());
    }
    
    
    public function test_invoke_shouldReturnApplicationResponseArticlePoco_whenDomainServiceHasBeenDeleteArticle(){
        $request=$this->createMock(DeleteArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('xxx');
        $this->domainService->expects($this->once())->method('__invoke')->willReturn(TestHelper::get()->makeArticle());
        
        $appResponse=call_user_func($this->app,$request);
        $this->assertFalse($appResponse->hasErrors());
        $this->assertNotEquals(ParkingPOCO::empty(), $appResponse->getArticle());
    }
    
    function test_invoke_shouldAppendAFlashMessageWIthUndoDelete_whenDomainServiceHasBeenDeleteArticle(){
        $request=$this->createMock(DeleteArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('xxx');
        $this->domainService->expects($this->once())->method('__invoke')->willReturn(TestHelper::get()->makeArticle('xxx','myArticleTitle'));
        $this->sessionManager->expects($this->once())->method('setFlash')->with(FlashMessage::success('Article myArticleTitle has been successfuly deleted'));
        
        $appResponse=call_user_func($this->app,$request);
    }
    
    function test_invoke_shouldAppendAFlashErrorMessage_whenAnErrorOccur(){
        
        $request=$this->createMock(DeleteArticleRequest::class);
        $request->expects($this->once())->method('getArticleId')->willReturn('xxx');
        $this->domainService->expects($this->once())->method('__invoke')->willThrowException(new ArticleException('id','an error occur'));
        $this->sessionManager->expects($this->once())->method('setFlash')->with(FlashMessage::error('an error occur'));
        
        $appResponse=call_user_func($this->app,$request);
    }
    
}
