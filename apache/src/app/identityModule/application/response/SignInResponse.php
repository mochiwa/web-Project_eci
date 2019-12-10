<?php

namespace App\Identity\Application\Response;

use App\Shared\Application\AbstractApplicationResponse;

/**
 * Description of SignInResponse
 *
 * @author mochiwa
 */
class SignInResponse extends AbstractApplicationResponse {
    /**
     * The view of the user
     * @var UserView 
     */
    private $userview;
    
    protected function __construct() {
        $this->userview= UserView::empty();
    }

    
    
    public static function of():self
    {
        return new self();
    }
    
    public function withUserView(UserView $userview):self
    {
        $this->userview=$userview;
        return $this;
    }
    
    public function getUserview(): UserView {
        return $this->userview;
    }
    
    
}
