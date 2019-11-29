<?php
namespace Framework\FileManager;
/**
 * Description of FileUploader
 *
 * @author mochiwa
 */
class FileUploader {
    const DEFAULT_UPLOAD_DIR="uploadFile";
    private $uploader;
    public function __construct(IUploader $uploader)
    {
        $this->uploader=$uploader;
    }
   
}
