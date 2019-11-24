<?php
namespace Framework\Renderer;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Renderer
 *
 * @author mochiwa
 */
class ViewBuilder {
    private $directoryPaths=[];
    private $globals=[];
    
    public function addPath(string $namepsace , string $path):void
    {
        if(empty($namepsace))
            throw new \InvalidArgumentException ("The namespace cannot be empty");
        if(empty($path))
            throw new \InvalidArgumentException ("The path cannot be empty");
        
        $this->directoryPaths[$namepsace]=$path;
    }
    
    public function build(string $view,array $parameters=[]):string
    {
        $namespace ="";
        $file="";
        
        if($view[0]!=='@')
            throw new \InvalidArgumentException ("The view must contain a namespace @");
        elseif($view[-1]==='/')
            throw new \InvalidArgumentException ("The view must be a file, it cannot ending with /");

        $namespace= $this->getNamespace($view);
        if(!array_key_exists($namespace, $this->directoryPaths))
            throw new \InvalidArgumentException ("The namespace : ".$namespace." has not been found");    
        
        $file=$this->getFilePath($namespace,$view);
        if(!file_exists($file))
            throw new \InvalidArgumentException ("The view file : ".$file." has not been found");   
        
        ob_start();
        extract($parameters);
        extract($this->globals);
        $viewBuilder=$this;
        require ($file);
        return ob_get_clean();
    }
    
    public function addGlobal(string $key,$data)
    {
        $this->globals[$key]=$data;
    }
    
    private function getNamespace(string $view)
    {
        return substr($view, 1, strrpos($view, '/')-1);
    }
    private function getView(string $view)
    {
        return substr(strrchr($view, "/"), 1);
    }
    private function getFilePath($namespace,$view)
    {
       return $this->directoryPaths[$namespace].DIRECTORY_SEPARATOR.$this->getView($view).'.php';
    }
    
}
