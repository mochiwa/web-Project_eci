<?php
namespace Test\Article\Model\Article\Service;

use App\Article\Model\Article\Service\CreateArticleRequest;
use App\Article\Model\Article\Service\CreateArticleService;
use PHPUnit\Framework\TestCase;
/**
 * Description of CreateArticleServiceTest
 *
 * @author mochiwa
 */
class CreateArticleServiceTest extends TestCase{
    private $service;
    public function setUp()
    {
        $this->service=new CreateArticleService();
    }
    
    function test_execute_shouldReturnANewArticle_whenNoErrorAreDetected()
    {
        $request=new CreateArticleRequest('ArticleTitle','picture',['att'=>"value"],"Description");
        $article=$this->service->execute($request);
        $this->assertNotNull($article);
    }
    
}
