<?php

namespace App\Identity\Application\Response;

use App\Shared\Application\AbstractApplicationResponse;

/**
 * Description of UserActivationResponse
 *
 * @author mochiwa
 */
class UserActivationResponse extends AbstractApplicationResponse {
   
    private $userView;

    public static function of(): self {
        return new self();
    }

    public function withUserView(UserView $userView):self
    {
        $this->userView=$userView;
        return $this;
    }
    
    public function getUserView() : UserView {
        return $this->userView;
    }
}


