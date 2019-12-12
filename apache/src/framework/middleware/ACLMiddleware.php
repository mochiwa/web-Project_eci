<?php

namespace Framework\Middleware;

use Framework\Acl\AbstractTarget;
use Framework\Acl\ACL;
use Framework\Acl\Role;
use Framework\Acl\Rule;
use Framework\Acl\Target;
use Framework\Router\Route;
use Framework\Session\SessionManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Description of ACLMiddleware
 *
 * @author mochiwa
 */
class ACLMiddleware implements MiddlewareInterface{
    /**
     *
     * @var SessionManager 
     */
    private $session;
    
    /**
     *
     * @var ACL 
     */
    private $acl;
    
    public function __construct(SessionManager $session, ACL $acl) {
        $this->session = $session;
        $this->acl = $acl;
    }

    
    
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $route=$request->getAttribute(Route::class);
        
        if(!$route){
            return $handler->handle($request,$handler);
        }
        
        $currentRole= $this->getCurrentRole();
        
        if(preg_match('/admin/', $route->name())){
            $rule= Rule::Allow(AbstractTarget::URL('admin'));
        }elseif(isset ($route->params()['action'])){
            
            $rule= Rule::Allow(AbstractTarget::ControllerAction($route->target(),$route->params()['action']));
            if($this->acl->hasRuleFor(Rule::Invert($rule), $currentRole)){
                $rule=Rule::Invert($rule);
            }
        }else{
            $rule= Rule::Allow(AbstractTarget::Controller($route->target()));
        }
        
        if(!$this->acl->isAllowed($currentRole, $rule)){
            $request=$request->withAttribute(Route::class, null);  
            
        }
        
        
        return $handler->handle($request,$handler);
        
    }
    
    
    private function getCurrentRole():Role{
        $user=$this->session->get(SessionManager::CURRENT_USER_KEY);
        if($user===null){
            return $this->acl->getRole('visitor');
        }elseif($user->isAdmin()){
            return $currentRole= $this->acl->getRole ('admin');
        }
        return $currentRole= $this->acl->getRole ('user');
    }

}
