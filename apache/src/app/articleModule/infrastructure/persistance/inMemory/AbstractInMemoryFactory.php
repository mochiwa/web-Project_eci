<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Infrastructure\Persistance\InMemory;

/**
 * Description of AbstractInMemoryFactory
 *
 * @author mochiwa
 */
class AbstractInMemoryFactory {

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

}
