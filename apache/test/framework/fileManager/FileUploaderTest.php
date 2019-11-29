<?php
namespace Test\Framework\FileManager;

use Exception;
use Framework\FileManager\FileUploaded;
use Framework\FileManager\FileUploader;
use Framework\FileManager\LocalUploader;
use PHPUnit\Framework\TestCase;
/**
 * Description of FileUploaderTest
 *
 * @author mochiwa
 */
class FileUploaderTest extends TestCase{
    const MY_TEST_DIRECTORY='myDirectory';
    private $uploader;
    private $file;
    
    public function setUp() {
       
    }
    
    public function tearDown() {
       
    }
    private function createFile(string $name='teste',string $ext='txt')
    {
        touch($name);
    }
   
    
}
