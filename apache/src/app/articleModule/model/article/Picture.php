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
    
    /**
     * @var string name of the file 
     */
    private $name;
    
    private function __construct(string $path,string $name) {
        $this->path=$path;
        $this->name=$name;
    }
    
    
    public static function of(string $path,string $name){
        return new self($path,$name);
    }
    
    public function path() : string{
        return $this->path;
    }
    
    public function name() : string {
        return $this->name;
    }
    
    public function fullPath():string{
        return $this->path.'/'.$this->name;
    }


}
