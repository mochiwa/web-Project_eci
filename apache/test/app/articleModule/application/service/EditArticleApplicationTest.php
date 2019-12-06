<?php

use App\Article\Application\Service\EditArticleApplication;
use App\Article\Application\Service\Response\EditResponse;
use App\Article\Model\Article\IArticleRepository;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;
use PHPUnit\Framework\TestCase;

/**
 * Description of EditArticleApplicationTest
 *
 * @author mochiwa
 */
class EditArticleApplicationTest extends TestCase {
    private $repository;
    private $validator;
    private $session;
    private $service;
    
    
    protected function setUp() {
        $this->repository=$this->createMock(IArticleRepository::class);
        $this->validator=$this->createMock(AbstractFormValidator::class);
        $this->session=$this->createMock(SessionManager::class);
        
        $this->service=new EditArticleApplication($this->repository, $this->validator, $this->session);
    }
    
    
    function test_invoke_shouldReturnNotFoundResponse_whenArticleToEditNotFoundInRepository()
    {
        
        $this->assertEquals(EditResponse::notFound(), call_user_func($this->service,'notExistingId',[]));
    }
    
}
