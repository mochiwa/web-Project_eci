<?php
namespace Framework\Acl;

/**
 * Description of Role
 *
 * @author mochiwa
 */
class Role {
    /**
     *
     * @var string
     */
    private $roleName;
    /**
     * set the level of the role,
     * for example admin = 99,visitor=0 ....
     * @var int 
     */
    private $level;
    
    
    private function __construct(string $roleName,int $level) {
       $this->roleName=$roleName;
       $this->level=$level;
    }
    
    
    public static function of(string $roleName ,int $level=0):self
    {
        return new self($roleName,$level);
    }
    
    
    public function name() :string
    {
        return $this->roleName;
    }

    public function getLevel() {
        return $this->level;
    }


}
