<?php

namespace App\Identity\Controller;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * This controller is responsible the action relative to the user account
 * like creation , connection , update ...
 *
 * @author mochiwa
 */
interface IUserController {
    
    /**
     * Must manage the user registration process
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    function register(RequestInterface $request): ResponseInterface;
    
    /**
     * Must manage the user activation process
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    function activation(RequestInterface $request) : ResponseInterface;
    
    /**
     * Must manage the user sign in process
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    function signIn(RequestInterface $request) : ResponseInterface;
    
    /**
     * Must manage the logout in process
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    function logout(RequestInterface $request) : ResponseInterface;
}

