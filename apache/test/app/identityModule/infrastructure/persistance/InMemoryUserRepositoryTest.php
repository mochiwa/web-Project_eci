<?php

use App\Identity\Infrastructure\Persistance\InMemoryUserRepository;
use Test\App\Identity\Model\User\UserRepositoryTest;

/**
 * Description of InMemoryUserRepository
 *
 * @author mochiwa
 */
class InMemoryUserRepositoryTest extends UserRepositoryTest{
    public function setUp() {
        $this->repository=new InMemoryUserRepository();
        
    }
    
    function tearDown() {
        $this->repository->clear();
    }
}
