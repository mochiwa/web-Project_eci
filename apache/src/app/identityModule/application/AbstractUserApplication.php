<?php

namespace App\Identity\Application;

use App\Identity\Application\Response\UserApplicationResponse;
use App\Identity\Application\Response\UserView;
/**
 * Description of AbstractUserApplication
 *
 * @author mochiwa
 */
abstract class AbstractUserApplication{
    /**
     *
     * @var UserView 
     */
    protected $userview;
    /**
     *
     * @var array 
     */
    protected $errors;
    
    
    protected function buildResponse() : UserApplicationResponse
    {
        $response= UserApplicationResponse::of();
        if(!empty($this->errors)){
            $response->withErrors($this->errors);
        }
        if(isset($this->userView)){
            $response->withUserView($this->userView);
        }
        return $response;
    }
}
