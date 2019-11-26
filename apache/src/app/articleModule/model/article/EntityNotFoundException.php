<?php
namespace App\Article\Model\Article;

/**
 * This exception must be raise
 * when entity not found in a repository
 *
 * @author mochiwa
 */
class EntityNotFoundException extends \RuntimeException{
    public function __construct(string $message) {
        parent::__construct($message, 404);
    }
}
