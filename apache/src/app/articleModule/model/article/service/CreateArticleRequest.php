<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\Title;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\Picture;

/**
 * Description of CreateArticleRequest
 *
 * @author mochiwa
 */
class CreateArticleRequest {
    private $title;
    private $picture;
    private $attributes;
    private $description;
    
    function __construct(string $title ,string $picture,Array $attributes,string $description) {
        $this->title=Title::of($title);
        $this->picture=Picture::of($picture);
        foreach ($attributes as $key=>$value) {
            $this->attributes[]= Attribute::of($key,$value);
        }
        $this->description=$description;
    }
    
    function getTitle() : Title {
        return $this->title;
    }

    function getPicture() : Picture{
        return $this->picture;
    }

    function getAttributes() : array{
        return $this->attributes;
    }

    function getDescription() : string{
        return $this->description;
    }


    
    
}
