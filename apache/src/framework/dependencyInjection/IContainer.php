<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\DependencyInjection;

/**
 * Description of IContainer
 *
 * @author mochiwa
 */
interface IContainer extends \Psr\Container\ContainerInterface{
    function appendDefinition(array $definitions);
    function make($key);
}
