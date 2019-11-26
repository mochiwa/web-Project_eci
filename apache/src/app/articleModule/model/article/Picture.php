<?php
namespace App\Article\Model\Article;

/**
 * Description of Picture
 *
 * @author mochiwa
 */
class Picture {
    /**
     * @var String path of file
     */
    private $path;
    
    private function __construct(string $path) {
        $this->path=$path;
    }
    
    
    public static function of(string $path)
    {
        return new self($path);
    }
}
