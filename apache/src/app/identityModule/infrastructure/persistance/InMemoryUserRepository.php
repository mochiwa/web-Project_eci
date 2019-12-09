<?php

namespace App\Identity\Infrastructure\Persistance;

use App\Identity\Model\User\Email;
use App\Identity\Model\User\EntityNotFoundException;
use App\Identity\Model\User\IUserRepository;
use App\Identity\Model\User\User;
use App\Identity\Model\User\UserId;
use App\Identity\Model\User\Username;
use App\Shared\Infrastructure\AbstractInMemoryRepository;

/**
 * Description of InMemoryUserRepository
 *
 * @author mochiwa
 */
class InMemoryUserRepository extends AbstractInMemoryRepository implements IUserRepository{
    
    public function __construct() {
        parent::__construct('UserRepository');
        $this->load();
    }

    /**
     * Append a user to the repository
     * @param User $user
     */
    public function addUser(User $user) {
        $this->data[]=$user;
        //$this->commit();
    }

    /**
     * Return the user found , else throw exception
     * @param UserId $id
     * @return User
     * @throws EntityNotFoundException
     */
    public function findUserById(UserId $id): User {
        foreach ($this->data as $user)
        {
            if($user->id()==$id)
            {
                return $user;
            }
        }
        throw new EntityNotFoundException();
    }

    /**
     * return true if the email is not used , false else
     * @param Email $email
     * @return bool
     */
    public function isEmailFree(Email $email): bool {
        foreach ($this->data as $user)
        {
            if($user->email()==$email)
                return false;
        }
        return true;
    }

    /**
     * return true if the username is not used, false else
     * @param Username $username
     * @return bool
     */
    public function isUsernameFree(Username $username): bool {
        foreach ($this->data as $user)
        {
            if($user->username()==$username)
                return false;
        }
        return true;
    }

    /**
     * Return an UsedId within a unique Id
     * @return UserId
     */
    public function nextId(): UserId {
        return UserId::of(uniqid());
    }
    
    /**
     * Update and commit the user if the user id was found
     * @param User $user
     * @return User
     */
    public function updateUser(User $user) {
        foreach ($this->data as $key => $value)
        {
            if($value->id()==$user->id())
            {
                $this->data[$key]=$user;
              //  $this->commit();
            }
        }
    }

    /**
     * Return the user found , else throw exception
     * @param Username $username
     * @return type
     * @throws EntityNotFoundException
     */
    public function findUserByUsername(Username $username) : User {
        foreach ($this->data as $user)
        {
            if($user->username()==$username)
            {
                return $user;
            }
        }
        throw new EntityNotFoundException();
    }

}
