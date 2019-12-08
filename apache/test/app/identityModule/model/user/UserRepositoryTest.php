<?php
namespace Test\App\Identity\Model\User;
use App\Identity\Model\User\Email;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\UserId;
use App\Identity\Model\User\Username;
use PHPUnit\Framework\TestCase;
use Test\App\Identity\Helper\UserBuilder;

/**
 * Description of UserRepositoryTest
 *
 * @author mochiwa
 */
abstract class UserRepositoryTest extends TestCase{
    protected $repository;
    
    protected function setUp() {
        $this->repository=new IUserRepository();
    }
    
    
    function test_nextId_shouldGenerateUniqueId()
    {
        $id_a=$this->repository->nextId();
        $id_b=$this->repository->nextId();
        
        $this->assertNotEquals($id_a, $id_b);
    }
    
    function test_isEmailFree_shouldReturnTrue_whenAnyUserUseTheEmail()
    {
        $this->assertTrue($this->repository->isEmailFree(Email::of('notExisting@mail.com')));
    }
    
    function test_isEmailFree_shouldReturnFalse_whenUserAlreadyUseTheEmail()
    {
        $email=Email::of('notExisting@mail.com');
        $this->repository->addUser(UserBuilder::of()->setEmail($email->emailToString())->build());
        
        $this->assertFalse($this->repository->isEmailFree(Email::of($email->emailToString())));
    }
    
    function test_isUsernameFree_shouldReturnTrue_whenAnyUserUseTheUsername()
    {
        $this->assertTrue($this->repository->isUsernameFree(Username::of('aUsername')));
    }
    
    function test_isUsernameFree_shouldReturnFalse_whenUserAlreadyUseTheUsername()
    {
        $username= Username::of('aUsername');
        $this->repository->addUser(UserBuilder::of()->setUsername($username->usernameToString())->build());
        
        $this->assertFalse($this->repository->isUsernameFree(Username::of($username->usernameToString())));
    }
    
    function test_addUser_shouldAppendTheUserInTheRepository()
    {
        $user = UserBuilder::of()->build();
        $this->repository->addUser($user);
        $userFound=$this->repository->findUserById($user->id());
        $this->assertSame($user, $userFound);
    }
    
    function test_findUserById_shouldThrowEntityNotFound_whenUserWithIdNotFound()
    {
        $this->expectException(EntityNotFoundException::class);
        $this->repository->findUserById(UserId::of('notExisting'));
    }
    
    function test_findUserById_shouldReturnTheUser_whenUserWithIdFound()
    {
        $user = UserBuilder::of()->build();
        $this->repository->addUser($user);
        $userFound=$this->repository->findUserById($user->id());
        $this->assertSame($user, $userFound);
    }
        
    
}
