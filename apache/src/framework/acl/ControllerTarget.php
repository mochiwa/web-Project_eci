<?php

namespace Framework\Acl;

/**
 * This class represent a controller#action target,
 * if a willdCard is set to action then that mean all action from controller
 *
 * @author mochiwa
 */
class ControllerTarget extends AbstractTarget{
    /**
     * This const means all action in controller
     */
    const  WILDCARD ='*';
    
    /**
     * action in the controller
     * @var string 
     */
    private $action;
    
    protected function __construct(string $controllerName,string $action) {
        parent::__construct($controllerName);
        
        $this->setAction($action);
    }

    /**
     * Use the abstract comparison + check if action match
     * @param AbstractTarget $target
     * @return bool
     */
    public function isMatch(AbstractTarget $target):bool {
        return $this->abstractComparison($target) && $this->isActionMatch($target->getAction());
    }
    
    /**
     * Return true if the action match or if <b>this</b> action is a wildcard
     * @param string $action
     * @return bool
     */
    private function isActionMatch(string $action):bool{
        return $this->getAction()===$action || $this->action===self::WILDCARD;
    }
    
    /**
     * Define the action
     * @param string $action
     */
    private function setAction(string $action)
    {
        $this->action = $action;
    }

    /**
     * Return the controller action
     * @return type
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * abstract target is comparable only if it's a ControllerTarget type
     * @param AbstractTarget $target
     * @return bool
     */
    public function isComparable(AbstractTarget $target) :bool {
        return $target instanceof self;
    }

}
