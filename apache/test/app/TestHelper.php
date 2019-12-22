<?php
namespace Test\App;
use App\Article\Model\Article\Article;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Title;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestHelper
 *
 * @author mochiwa
 */
class TestHelper {
    private static $instance;
    
    public static function get()
    {
        if(self::$instance===null)
            self::$instance=new TestHelper();
        return self::$instance;
    }
    
    public function makeArticle($id='aaa',$title='aTitle',$picture='a/path/to/pics',array $attributes=[], $description='a desciption')
    {
        return Article::newArticle(ArticleId::of($id),
                Title::of($title),
                Picture::of('/path',$picture),
                $attributes, $description);
    }
}
