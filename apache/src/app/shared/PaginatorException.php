<?php

namespace App\Shared;

use InvalidArgumentException;

/**
 * Description of PaginatorException
 *
 * @author mochiwa
 */
class PaginatorException extends InvalidArgumentException{
    public function __construct(string $message) {
        parent::__construct($message);
    }

}
