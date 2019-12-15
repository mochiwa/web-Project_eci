<?php

namespace App\Shared\Infrastructure;

use Framework\Connection\ITransaction;

/**
 * Description of InMemoryTransaction
 *
 * @author mochiwa
 */
class InMemoryTransaction implements ITransaction{
    private $repository;
    private $backup;
    
    function __construct(AbstractInMemoryRepository $repository) {
        $this->repository=$repository;
    }
    
    
    
    public function breakAutoCommit() {
        $this->backup=$this->repository->getData();
    }

    public function commit() {
        $this->repository->commit();
    }

    public function rollback() {
        $this->repository->setData($data);
        $this->commit();
    }

}
