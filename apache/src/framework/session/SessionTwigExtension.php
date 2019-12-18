<?php

namespace Framework\Session;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Description of SessionTwigExtension
 *
 * @author mochiwa
 */
class SessionTwigExtension extends AbstractExtension{
    private $session;
    
    public function __construct(SessionManager $session) {
        $this->session=$session;
    }
    


    public function getTests() {
        return [new \Twig\TwigTest('connected', [$this,'isUserConnected'])];
    }
    
    public function isUserConnected()
    {
        return $this->session->has(SessionManager::CURRENT_USER_KEY);
    }

}
