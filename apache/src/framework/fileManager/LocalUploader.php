<?php

namespace Framework\FileManager;

/**
 * Description of LocalUploader
 *
 * @author mochiwa
 */
class LocalUploader implements IUploader {
    
    public function upload(string $src, string $dest) {
        copy($src, $dest);
    }

}
