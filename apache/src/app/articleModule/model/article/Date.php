<?php
namespace App\Article\Model\Article;

/**
 * Description of Date
 *
 * @author mochiwa
 */
class Date {
    private $timestamp;
    
    private function __construct(int $timestamp) {
        $this->timestamp=$timestamp;
    }
    
    public static function fromTimeStamp(int $timestamp)
    {
        return new self($timestamp);
    }
    
}
