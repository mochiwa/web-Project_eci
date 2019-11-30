<?php
namespace Test\App\Article\Application\Service;

use App\Article\Application\Service\ArticleCreationService;
use App\Article\Model\Article\IArticleRepository;
use Framework\FileManager\FileUploader;
use Framework\Validator\AbstractFormValidator;
use PHPUnit\Framework\TestCase;
/**
 * Description of ArticleCrudService
 *
 * @author mochiwa
 */
class ArticleCreationServiceTest extends TestCase{
    private $repository;
    private $validator;
    private $uploader;
    private $service;
    private $post;
    
    function setUp() {
        $this->repository=$this->createMock(IArticleRepository::class);
        $this->validator=$this->createMock(AbstractFormValidator::class);
        $this->uploader=$this->createMock(FileUploader::class);
        $this->service=new ArticleCreationService($this->repository,$this->validator,$this->uploader);
    $this->post=['title'=>'test','picture'=>'aPicture','city'=>'aCity','name'=>'aName','place'=>'45','description'=>'lorem ipsum'];
    }
    
    function test_create_shouldReturnArrayWithErrors_whenFormIsNotValid()
    {
        $this->validator->expects($this->once())->method('validate')->willReturn(false);
        $this->validator->method('getErrors')->willReturn(['errors'=>'an error']);
        
        $response=$this->service->execute([]);
        $this->assertEquals(['errors'=>'an error'], $response);
    }
    
    function test_create_shouldUploadPictureOfTheArticle_whenNoErrorOccur()
    {
        $this->validator->expects($this->once())->method('validate')->willReturn(true);
        $this->uploader->expects($this->once())->method('uploadToDefault');
        $this->service->execute($this->post);
    }
    
    function test_create_shouldReturnFlashSuccessMessage_whenNoErrorOccur()
    {
        $this->validator->expects($this->once())->method('validate')->willReturn(true);
        $this->uploader->expects($this->once())->method('uploadToDefault');
        $this->assertEquals(['success'=>['flash' =>'The article "test" has been created !']], $this->service->execute($this->post));
    }
}
