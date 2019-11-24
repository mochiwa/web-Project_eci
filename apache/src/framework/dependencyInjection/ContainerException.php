<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\DependencyInjection;

/**
 * Description of ContainerException
 *
 * @author mochiwa
 */
class ContainerException extends \Exception implements \Psr\Container\ContainerExceptionInterface{
    
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
