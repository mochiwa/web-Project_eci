<?php

namespace Framework\FileManager;

/**
 * This class is responsible to convert an object that implement
 * the FileUploadedInterface to common language
 *
 * @author mochiwa
 */
class FileUploadFormater{ 
    /**
     * Contain file uploaded , each file must implement
     * FileUploadedInterface from PSR
     * @var  array
     */
    private $files;
    
    public function __construct(array $filesUploaded) {
        $this->files = $filesUploaded;
    }
    
    public static function of(array $filesUploaded):self{
        return new self($filesUploaded);
    }
    
    /**
     * Return the path of the uploaded file if exist,else empty string
     * @param string $picture
     * @return string
     */
    public function pathOf(string $picture)  {
        try{
            return $this->files[$picture]->getStream()->getMetaData('uri');
        } catch (\Exception $ex) {
            return '';
        }
    }

    
}
