<?php
namespace App\Identity\Model\User;
/**
 * Description of IUserRepository
 *
 * @author mochiwa
 */
interface IUserRepository {
    /**
     * Must return a unique user id 
     * @return UserId
     */
    function nextId() : UserId;
    /**
     * Must return true if email is not already used
     * by another user
     * @return bool
     */
    function isEmailFree(Email $email): bool ;
    /**
     * Must return true if username is not already used
     * by another user
     * @return bool
     */
    function isUsernameFree(Username $username):bool ;
    
    /**
     * Must append an user to the repository
     * @param User $user
     */
    function addUser(User $user);
    
    /**
     * Must return an user or throw
     * @param \App\Identity\Model\User\App\Identity\Model\User\UserId $id
     * @return User
     */
    function findUserById(UserId $id): User;
}
