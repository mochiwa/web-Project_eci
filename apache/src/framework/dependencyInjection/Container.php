<?php
namespace Framework\DependencyInjection;
/**
 * Class Responsible to inject dependencies;
 * @author mochiwa
 */
class Container  implements \Psr\Container\ContainerInterface{
    /**
     * @var array 
     */
    private $container; 
    /**
     * @var array 
     */
    private $instances;
    /**
     * @var array
     */
    private $factories;
    
    public function __construct() {
        $this->container=[];
        $this->instances=[];
        $this->factories=[];
    }
    
    
    
    /**
     * Append a class
     * @param string   $key   the key to found class ,(namespace + class name recommended )
     * @param callable $value a function that call the class with your own parameter
     */
    public function set(string $key, callable $value) {
        $this->container[$key] = $value;
    }

    /**
     * allow to load multiple definition from an array
     * @param array $definitions
     */
    public function appendDefinition(array $definitions)
    {
        foreach ($definitions as $key => $value)
        {
           $this->set($key, $value);
        }
    }
    /**
     * Return an instance linked to the key, it use lazy loading
     * and return always the same instance.
     * @param type $key
     * @return mixed
     * @throws ContainerException when the container not contain the key
     */
    public function get($key) {
        if(!$this->has($key))      
          throw new ContainerException("The key :".$key. " not found in container". sizeof($this->container));
        if(!array_key_exists($key, $this->instances))
            $this->instances[$key]=$this->container[$key]($this);
        return $this->instances[$key];
    }

    public function has($key): bool {
        return array_key_exists($key, $this->container);
    }
    
    /**
     * Create a new instance
     * @param type $key
     * @return mixed
     * @throws ContainerException when the container not contain the key
     */
    public function make($key)
    {
        if(!$this->has($key))       
            throw new ContainerException("The key :".$key. "not found in container");
        return $this->container[$key]();
    }

    
    
}
