<?php

use Framework\Acl\AbstractTarget;
use Framework\Acl\ACL;
use Framework\Acl\ACLException;
use Framework\Acl\Role;
use Framework\Acl\Rule;
use PHPUnit\Framework\TestCase;

/**
 * Description of ACLTest
 *
 * @author mochiwa
 */
class ACLTest extends TestCase{
    private $acl;
    
    protected function setUp() {
        $this->acl=new ACL();
    }
    
    
    function test_addRole_shouldAppendRole_whenRoleIsNotAlreadyPresent()
    {
        $role=Role::of('myRole');
        $this->acl->addRole($role);
        $this->assertTrue($this->acl->hasRole($role));
    }
    
    function test_addRole_shouldThrowACLException_whenRoleAlreadyExist()
    {
        $role=Role::of('myRole');
        $this->acl->addRole($role);
        $this->expectException(ACLException::class);
        $this->acl->addRole($role);
    }
    
    function test_addRole_shouldThrowACLException_whenRoleNameAlreadyExist()
    {
        $role=Role::of('myRole');
        $overide=Role::of('myRole',2);
        $this->acl->addRole($role);
        $this->expectException(ACLException::class);
        $this->acl->addRole($overide);
    }
    
    function test_addRole_shouldCreateAnEmptyArrayOfRules()
    {
        $role=Role::of('myRole');
        $this->acl->addRole($role);
        $this->assertEmpty($this->acl->listOfRulesFor($role));
    }
    
    function test_listOfRulesFor_shouldThrowAclException_whenRoleNotFoundInACL()
    {
        $this->expectException(ACLException::class);
        $this->acl->listOfRulesFor(Role::of('aRole'));
    }
    
    function test_addRule_shouldAppendTheRuleForTheRole_whenTheRoleExistInACL()
    {
        $role=Role::of('aRole');
        $rule=Rule::Allow(AbstractTarget::URL('/login'));
        $this->acl=new ACL([$role]);
        $this->acl->addRule($role,$rule);
        $this->assertEquals([$rule], $this->acl->listOfRulesFor($role));
    }
    
    function test_addRule_shouldThrowACLException_whenTheACLHasTheRole()
    {
        $role=Role::of('aRole');
        $rule=Rule::Allow(AbstractTarget::URL('/login'));
        
        $this->expectException(ACLException::class);
        $this->acl->addRule($role,$rule);
    }
    
    function test_isAllowed_shouldThrowACLException_whenTheACLHasNotTheRole()
    {
        $rule=Rule::Allow(AbstractTarget::URL('/login'));
        $this->expectException(ACLException::class);
        $this->acl->isAllowed(Role::of("aRole"),$rule);
    }
    
    function test_isAllowed_shouldReturnFalse_whenTheRoleHasNotTheRule(){
        $rule=Rule::Allow(AbstractTarget::URL('/login'));
        $role=Role::of("aRole");
        $this->acl=new ACL([$role]);
        $this->assertFalse($this->acl->isAllowed($role,$rule));
    }
    
    function test_isAllowed_shouldReturnFalse_whenTheRoleHasDenyRule(){
        $role=Role::of("aRole");
        $rule= Rule::Deny(AbstractTarget::URL("aTarget"));
        $this->acl=new ACL([$role],[$role->name()=>[$rule]]);
        $this->assertFalse($this->acl->isAllowed($role,$rule));
    }

    function test_isAllowed_shouldReturnTrue_whenTheRoleHasAllowRule(){
        $role=Role::of("aRole");
        $rule= Rule::Allow(AbstractTarget::URL("aTarget"));
        $this->acl=new ACL([$role],[$role->name()=>[$rule]]);
        $this->assertTrue($this->acl->isAllowed($role,$rule));
    }
    function test_isAllowed_shouldReturnFalse_whenRuleTargetNotMatch(){
        $role=Role::of("aRole");
        $target=$this->createMock(AbstractTarget::class);
        $target->expects($this->any())->method('isMatch')->willReturn(false);
        $rule= Rule::Allow($target);
        
        $this->acl=new ACL([$role],[$role->name()=>[$rule]]);
        $this->assertFalse($this->acl->isAllowed($role,$rule));
    }
    
    function test_isAllowed_shouldReturnTrue_whenARoleWithALevelLessHasTheRule(){
        $roles=[Role::of("admin",99),Role::of("user",1)];
        $rule=Rule::Allow(AbstractTarget::URL("/aTarget"));
        $rules=[$roles[1]->name()=>[$rule]];
        $this->acl=new ACL($roles,$rules);
        $this->assertTrue($this->acl->isAllowed(Role::of("admin",99),$rule));
    }
    function test_isAllowed_shouldReturnFalse_whenRoleHasNoteRuleAndAnyOtherRoleIsAllowed(){
        $roles=[Role::of("admin",99),Role::of("user",1)];
        $rule=Rule::Deny(AbstractTarget::URL("/aTarget"));
        $rules=[$roles[1]->name()=>[$rule]];
        $this->acl=new ACL($roles,$rules);
        $this->assertFalse($this->acl->isAllowed(Role::of("admin",99),$rule));
     }
     
     
    function test_constructor_shouldAppendEachRole_whenParametersContainsRoleArray()
    {
        $this->acl=new ACL([Role::of("a"),Role::of("b")]);
        $this->assertEquals([Role::of("a"),Role::of("b")],$this->acl->roles());
    }
    function test_constructor_shouldAppendEachRuleToRole_whenParametersContainsRuleArray()
    {
        $roles=[Role::of("a"),Role::of("b")];
        $rules=[$roles[0]->name()=>[Rule::Allow(AbstractTarget::URL("aTarget"))],$roles[1]->name()=>[Rule::Deny(AbstractTarget::URL("aTarget"))]];
        $this->acl=new ACL($roles,$rules);
        $this->assertEquals($rules, $this->acl->rules());
    }
    function test_constructor_shouldThrowException_whenARoleIsPresentInRuleButNotInRole()
    {
        $roles=[Role::of("a"),Role::of("b")];
        $rules=[$roles[0]->name()=>[Rule::Allow(AbstractTarget::URL("aTarget"))],'not existing'=>[Rule::Deny(AbstractTarget::URL("aTarget"))]];
        
        $this->expectException(ACLException::class);
        $this->acl=new ACL($roles,$rules);
    }
    
    function test_hasRule_shouldReturnTrue_whenRoleHasRule()
    {
        $roles=[Role::of("a")];
        $rules=[Role::of("a")->name()=>[Rule::Allow(AbstractTarget::URL("aTarget"))]];
        $this->acl=new ACL($roles,$rules);
        $this->assertTrue($this->acl->hasRuleFor(Rule::Allow(AbstractTarget::URL("aTarget")),Role::of("a")));
    }
   
   function test_load_shouldReplaceAllRuleAndRoleByEmpty_whenArrayInArgumentAreEmpty(){
        $roles=[Role::of("a")];
        $rules=[Role::of("a")->name()=>[Rule::Allow(AbstractTarget::URL("aTarget"))]];
        $this->acl=new ACL($roles,$rules);
        
        $this->acl->loadFromArray([Acl::ROLES_INDEX=>[],Acl::RULES_INDEX=>[Acl::ALLOW_INDEX=>[],Acl::DENY_INDEX=>[]]]);
        $this->assertEquals([], $this->acl->roles());
        $this->assertEquals([], $this->acl->rules());
    }
    
    function test_load_shouldThrowAclException_whenRoleIndexNotExist(){
        $array=[Acl::RULES_INDEX=>[]];
        $this->expectException(ACLException::class);
        $this->acl->loadFromArray($array);
    }
    function test_load_shouldThrowAclException_whenRuleIndexNotExist(){
        $array=[Acl::ROLES_INDEX=>[]];
        $this->expectException(ACLException::class);
        $this->acl->loadFromArray($array);
    }
    
    function test_load_shouldAppendRuleForEachRole(){
        $array=[Acl::ROLES_INDEX=>[Role::of('admin',3),Role::of('user',2),Role::of('visitor',1)],
            Acl::RULES_INDEX=>[
                'admin'=>[
                    'allow' => [AbstractTarget::URL('/'),AbstractTarget::URL('/admin')],
                    'deny'=>[]
                ],
                'user'=>[
                    'allow' => [AbstractTarget::Controller('user')],
                    'deny'=>[]
                ],
                'visitor'=>[
                    'allow' => [AbstractTarget::URL('/login'),AbstractTarget::URL('/register')],
                    'deny'=>[]
                ]
            ]
        ];
        $this->acl->loadFromArray($array);
        
        
        $this->assertTrue($this->acl->isAllowed(Role::of('admin',3), Rule::Allow(AbstractTarget::URL('/admin'))));
        $this->assertTrue($this->acl->isAllowed(Role::of('admin',3), Rule::Allow(AbstractTarget::URL('/login'))));
        $this->assertTrue($this->acl->isAllowed(Role::of('visitor',1), Rule::Allow(AbstractTarget::URL('/login'))));
        $this->assertTrue($this->acl->isAllowed(Role::of('user',2), Rule::Allow(AbstractTarget::URL('/register'))));
        
        $this->assertTrue($this->acl->isAllowed(Role::of('user',2), Rule::Allow(AbstractTarget::Controller('user'))));
        $this->assertTrue($this->acl->isAllowed(Role::of('user',2), Rule::Allow(AbstractTarget::ControllerAction('user','create'))));
    }
}