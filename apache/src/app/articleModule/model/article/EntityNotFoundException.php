<?php
namespace App\Article\Model\Article;

/**
 * Description of EntityNotFoundException
 *
 * @author mochiwa
 */
class EntityNotFoundException extends \RuntimeException{
    public function __construct(string $message) {
        parent::__construct($message, 404);
    }
}
