<?php

namespace Framework\Connection;
use Framework\Connection\ITransaction;


/**
 * Description of AtomicRemoteOperation
 *
 * @author mochiwa
 */
class AtomicRemoteOperation {
    private $transactionManager;
    public function __construct(ITransaction $transactionManager) {
        $this->transactionManager=$transactionManager;
    }
    
    public function __invoke(callable $callback,array $arguments) {
        try{
            $this->transactionManager->breakAutoCommit();
            $dataReturned= call_user_func_array($callback,$arguments);
            $this->transactionManager->commit();
        } catch (\Exception $ex) {
            $this->transactionManager->rollback();
        }
        return $dataReturned;
    }
}
