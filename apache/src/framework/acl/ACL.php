<?php

namespace Framework\Acl;

/**
 * Description of ACL
 *
 * @author mochiwa
 */
class ACL {
    const ROLES_INDEX='roles';
    const RULES_INDEX='rules';
    const ALLOW_INDEX='allow';
    const DENY_INDEX='deny';
    /**
     *
     * @var array 
     */
    private $rules=[];

    /**
     *
     * @var array 
     */
    private $roles=[];

    function __construct(array $roles=[],array $rules=[]) {
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        
        foreach ($rules as $role => $listOfRules){
            if(!isset($this->rules[$role])){
                throw new ACLException('The role <'.$role.'> Is not present in roles from constructor');
            }
            $this->rules[$role]=$listOfRules;
        }
    }
    
    public static function fromArray(array $array=[])
    {
        $acl=new self();
        $acl->loadFromArray($array);
        return $acl;
    }
    
    /**
     * Append a role if any role with the same name is already present
     * @param Role $role
     * @return \self
     * @throws ACLException
     */
    function addRole(Role $role):self{
        if($this->hasRole($role)){
            throw new ACLException('A role with the name <'.$role->name().'> is already present');
        }
        $this->roles[]=$role;
        $this->rules[$role->name()]=[];
        return $this;
    }
    
    /**
     * Return true if a role with the role name in parameters
     * it's already present
     * @param Role $role
     * @return bool
     */
    function hasRole(Role $role): bool {
        foreach ($this->roles as $i) {
            if($role->name()===$i->name()){
                return true;
            }
        }
        return false;
    }
    
    /**
     * Return all rule for the role
     * @param Role $role
     * @return array
     */
    function listOfRulesFor(Role $role):array{
        if(!$this->hasRole($role)){
            throw new ACLException("Cannot return list of rules , cause the role not found");
        }
        return $this->rules[$role->name()];
    }
    
    /**
     * Append a rule linked to a role , the role must be add before to be linked
     * @param Role $role
     * @param Rule $rule
     * @return \self
     * @throws ACLException if the role is not knew by the ACL
     */
    function addRule(Role $role,Rule $rule):self{
        if(!$this->hasRole($role)){
            throw new ACLException("Cannot append a rule, cause the role not found");
        }
        $this->rules[$role->name()][]=$rule;
        return $this;
    }
    
    /**
     * check if the role is allowed execute the rule,
     * if role has not the rule , or if the rule is deny return false
     * @param Role $role
     * @param Rule $rule
     * @return bool
     * @throws ACLException
     */
    function isAllowed(Role $role, Rule $rule):bool{
        if(!$this->hasRole($role)){
            throw new ACLException("Cannot check if the role is allowed, cause the role not found");
        }
        if(!$this->hasRuleFor($rule, $role)){
            return $this->isLessRoleAllowed($role,$rule);
        }
        return $this->getRuleLinkedToRole($rule,$role)->isAllowed();
    }
    
    private function isLessRoleAllowed(Role $caller, Rule $rule) {
        $role=$this->getLessRoleAllowed($rule);
        return $role===null ? false : $role->getLevel()<=$caller->getLevel();
    }
    
    private function getLessRoleAllowed($rule) {
        foreach ($this->roles as $role) {
            if ($this->hasRuleFor($rule, $role)  && $this->isAllowed($role, $rule)) {
                return $role;
            }
        }
        return null;
    }
    

    /**
     * Return all roles
     * @return array
     */
    function roles():array{
        return $this->roles;
    }
    
    /**
     * list of rules
     * @return array
     */
    function rules():array{
        return $this->rules;
    }
    /**
     * Check if the role has the rule
     * @param \Framework\Acl\Rule $rule
     * @param \Framework\Acl\Role $role
     * @return bool
     */
    function hasRuleFor(Rule $rule,Role $role):bool{
        $setOfRules=$this->listOfRulesFor($role);
        if($this->isRuleInSet($rule, $setOfRules)){
            $tmp=$this->getRuleLinkedToRole($rule, $role);
            return $tmp->getTarget()->isMatch($rule->getTarget());
        }
        return false;
    }
    
    private function isRuleInSet(Rule $rule,$set=[]){
        foreach ($set as $i) {
            if($i->getTarget()->isMatch($rule->getTarget()))
                return true;
        }
        return false;
    }
    
    /**
     * Return the rule linked to the role
     * @param \Framework\Acl\Rule $rule
     * @param \Framework\Acl\Role $role
     * @return \Framework\Acl\Rule|null
     */
    private function getRuleLinkedToRole(Rule $rule,Role $role) : ?Rule
    {
        $setOfRules=$this->listOfRulesFor($role);
        return $setOfRules[array_search($rule, $setOfRules)];
    }
    
    /**
     * Load an acl from an array
     * Example of array :
     * [Acl::ROLES_INDEX=>[Role::of('admin',3),Role::of('user',2),Role::of('visitor',1)],
            Acl::RULES_INDEX=>[
                'admin'=>[
                    'allow' => ['/','/admin'],
                    'deny'=>[]
                ],
                'user'=>[
                    'allow' => ['/user'],
                    'deny'=>[]
                ]
     * ]]
     * @param array $array
     * @throws ACLException
     */
    public function loadFromArray(array $array)
    {
        $this->roles=[];
        $this->rules=[];
        
        if(!isset($array[self::ROLES_INDEX])){
            throw new ACLException('The "'.self::ROLES_INDEX.'" index is not defiend');
        }elseif(!isset($array[self::RULES_INDEX])){
            throw new ACLException('The "'.self::RULES_INDEX.'" index is not defiend');
        }
        
        foreach ($array[self::ROLES_INDEX] as $role) {
            $this->addRole($role);
        }
        
        foreach ($array[self::RULES_INDEX] as $roleName => $blockOfRules) {
            foreach ($blockOfRules as $predicat=>$rules) {
                if($predicat === self::ALLOW_INDEX)
                {
                    foreach ($rules as $target){
                        $this->addRule($this->getRole($roleName), Rule::Allow($target));
                    }
                }
                elseif($predicat === self::DENY_INDEX)
                {
                    foreach ($rules as $target){
                        $this->addRule($this->getRole($roleName), Rule::Deny($target));
                    }
                }else{
                    throw new ACLException('Only '.self::ALLOW_INDEX.' and '.self::DENY_INDEX.' are allowed as predicat current=>'.$rule[0]);
                }
            }
        }
    }
    
    
    public function getRole(string $roleName): ?Role{
        foreach ($this->roles() as $role) {
            if($role->name()===$roleName)
            {
                return $role;
            }
        }
        return null;
    }
}
