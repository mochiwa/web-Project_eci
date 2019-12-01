<?php
namespace App\Article\Model\Article;

/**
 * Description of Date
 *
 * @author mochiwa
 */
class Date{
    private $timestamp;
    
    private function __construct(int $timestamp) {
        $this->timestamp=$timestamp;
    }
    
    /**
     * Build a date from a timestamp
     * @param int $timestamp
     * @return \self
     */
    public static function fromTimeStamp(int $timestamp)
    {
        return new self($timestamp);
    }
    
    public function toHumainReadable(): string
    {
        return date('d F Y \a\t H:m',$this->timestamp);
    }
    public function toHumainReadableShort(): string
    {
        return date('d\/m\/Y \a\t H:m',$this->timestamp);
    }
    
}
