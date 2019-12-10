<?php
namespace Framework\Connection;
/**
 * Description of ITransaction
 *
 * @author mochiwa
 */
interface ITransaction {
    
    function breakAutoCommit();
    
    function commit();
    
    function rollback();
}
