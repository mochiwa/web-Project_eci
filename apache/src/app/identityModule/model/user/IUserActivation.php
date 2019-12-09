<?php

namespace App\Identity\Model\User;

/**
 * the interface to active user , by mail , sms , ...
 *
 * @author mochiwa
 */
interface IUserActivation {
    
    /**
     * should manage the activation send process and
     * then return data send
     * @param \App\Identity\Model\User\User $user
     * @return string
     */
    function sendActivationRequest(User $user) :string;
}
