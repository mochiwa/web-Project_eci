<?php
namespace App\Shared\Infrastructure;

/**
 * Description of AbstractInMemoryFactory
 *
 * @author mochiwa
 */
abstract class AbstractInMemoryRepository {

    const DIR = './inMemory/';

    protected $data = [];
    private $file;

    function __construct(string $filename) {
        if (!file_exists(self::DIR)) {
            mkdir(self::DIR);
        }
        if (!file_exists(self::DIR . $filename)) {
            $handler = fopen(self::DIR . $filename, 'w');
            fclose($handler);
            $this->data = [];
        }
        $this->file = self::DIR . $filename;
    }

    public function commit() {
        file_put_contents($this->file, serialize($this->data));
    }

    public function load() {
        $this->data = unserialize(file_get_contents($this->file));
        if (!$this->data)
            $this->data = [];
    }

    public function clear() {
        if (file_exists($this->file))
            unlink($this->file);
    }
    
    public function getData() : array{
        return $this->data;
    }
    public function setData(array $data):void{
        $this->data=$data;
    }

}
