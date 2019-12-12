<?php 
namespace Framework\Acl;

/**
 * This class is responsible to represent
 * a target for the a Rule in ACL
 *
 * @author mochiwa
 */
abstract class AbstractTarget {
    /**
     *
     * @var The name of the target
     */
    protected $name;
    
    
    protected function __construct($name) {
        $this->setName($name);
    }

    /**
     * Return a target for a link / url name 
     * @param string $name
     * @return \self
     */
    public static function URL(string $name) : self{
        return new URLTarget($name);
    }
    
    /**
     * Return a controller Target with a specific action
     * @param string $name
     * @param string $action
     * @return \self
     */
    public static function ControllerAction(string $name,string $action) : self{
        return new ControllerTarget($name,$action);
    }
    
    /**
     * Return a controller Target with a wildCard,
     * that mean 'the whole controller'
     * @param string $name
     * @return \self
     */
    public static function Controller(string $name) : self{
        return new ControllerTarget($name,ControllerTarget::WILDCARD);
    }
    
    /**
     * must Return true if two target semantic match
     */
    abstract function isMatch(AbstractTarget $target) :bool;
    /**
     * Must return true or false if abstractTarget and concrete class
     * will be able to be compared
     */
    abstract function isComparable(AbstractTarget $target) :bool;
    
    /**
     * return the name of the target
     * @return string
     */
    public function getName() :string {
        return $this->name;
    }
    /**
     * set the name of the target
     * @param string $name
     */
    protected function setName(string $name){
        $this->name=$name;
    }
    
    /**
     *  The base comparison for target , check if the it's comparable and if name match
     * @param \Framework\Acl\AbstractTarget $comparable
     * @return bool
     */
    protected function abstractComparison(AbstractTarget $comparable) :bool {
        return $this->isComparable($comparable) && $this->name === $comparable->getName() ;
    }
}
