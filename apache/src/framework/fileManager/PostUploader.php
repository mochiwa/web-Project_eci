<?php

namespace Framework\FileManager;

/**
 * Description of PhpUploader
 *
 * @author mochiwa
 */
class PostUploader implements IUploader{
    
    public function upload($file,$path){
        move_uploaded_file($file,  $path);
    }
    
}
