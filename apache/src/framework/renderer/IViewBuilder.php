<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Framework\Renderer;

/**
 * Description of IViewBuilder
 *
 * @author mochiwa
 */
interface IViewBuilder {
    /**
     * Add a path to a view with a name space
     * @param string $namepsace
     * @param string $path
     * @return void
     */
    function addPath(string $namepsace , string $path):void;
    /**
     * Return the view 
     * @param string $view
     * @param array $parameters
     * @return string
     */
    function build(string $view,array $parameters=[]):string;
    /**
     * Add variable accessible anywhere 
     * @param string name to use to find variable
     * @param type $data
     */
    function addGlobal(string $key,$data);
}
