<?php

namespace App\Identity\Infrastructure\Service;

use App\Identity\Model\User\User;
use Framework\Session\SessionManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

/**
 * Description of UserTwigExtension
 *
 * @author mochiwa
 */
class UserTwigExtension extends AbstractExtension{
    /**
     *
     * @var SessionManager 
     */
    private $session;
    
    public function __construct(SessionManager $session) {
        $this->session=$session;
    }
    


    public function getTests() {
        return [new TwigTest('connected', [$this,'isUserConnected']),
            new TwigTest('admin',[$this,'isAdmin'])];
    }
    
    public function getFunctions() {
        return [];
    }

    
    public function isUserConnected() : bool{
        return $this->session->has(SessionManager::CURRENT_USER_KEY);
    }
    
    public function isAdmin(?User $user):bool{
        if(is_null($user)){
            return $this->session->get(SessionManager::CURRENT_USER_KEY)->isAdmin();
        }
        return $user->isAdmin();
    }
}
