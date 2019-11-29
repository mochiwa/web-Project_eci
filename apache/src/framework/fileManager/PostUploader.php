<?php

namespace Framework\FileManager;

/**
 * Description of PhpUploader
 *
 * @author mochiwa
 */
class PostUploader implements IUploader{
    
    
    
    public function upload(string $src, string $dest) {
        move_uploaded_file($src,  $dest);
    }

}
