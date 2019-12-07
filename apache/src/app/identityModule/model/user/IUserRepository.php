<?php
namespace App\Identity\Model\User;
/**
 * Description of IUserRepository
 *
 * @author mochiwa
 */
interface IUserRepository {
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
}
