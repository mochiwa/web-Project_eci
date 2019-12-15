<?php

namespace App\Identity\Infrastructure\Service;

use App\Identity\Model\User\IUserActivation;
use App\Identity\Model\User\User;
use Framework\Html\Link;
use Framework\Router\IRouter;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;

/**
 * Description of ActivationByLink
 *
 * @author mochiwa
 */
class ActivationByLink implements IUserActivation{
    private $router;
    private $sessionManager;
    
    function __construct(IRouter $router, SessionManager $sessionManager) {
        $this->router=$router;
        $this->sessionManager=$sessionManager;
    }
    public function sendActivationRequest(User $user) {
        $userId=$user->id()->idToString();
        $url=$this->router->generateURL('user.selected',['action'=>'activation','id'=>$userId]);
        $htmlLink=new Link($url,'here');
        
        $this->sessionManager->setFlash(FlashMessage::success(''
                . 'For validate your account please click '.$htmlLink->toHtml()));
    }

    

}
