<?php
namespace Framework\DependencyInjection;

use ReflectionClass;
/**
 * Class Responsible to inject dependencies;
 * @author mochiwa
 */
class Container  implements IContainer{
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
        if(!array_key_exists($key, $this->instances))
        {
            if($this->has($key))
            {
                $this->instances[$key]=$this->container[$key]($this);
            }
            else
            {
                $this->instances[$key]=($this->resolve($key));
            }
        }
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

    /**
     * Try to resolve the class , if class not found throw exception,
     * if class constructor contain arg try tro resolve if any get a new instance
     * @param string $key
     * @return type
     * @throws DIException
     */
    private function resolve(string $key) {
        $instance = null;
        $reflected_class = new ReflectionClass($key);
        
        if ($reflected_class->isInstantiable()) {
            $constructor = $reflected_class->getConstructor();
            if ($constructor)
                $instance = $reflected_class->newInstanceArgs($this->buildArguments($constructor));
            else
                $instance = $reflected_class->newInstance();
        } else
            throw new DIException('The ' . $key . ' is not Instanciable');
        return $instance;
    }
    
    /**
     * Try to build argument , if the argument is a class the call get method to try to resolve it,
     * or if the argument is not a class it must have a default value , any else an exception is throw
     * @param  [type] $constructor [description]
     * @return [type]              [description]
     */
    private function buildArguments($constructor) {
        $parameters = $constructor->getParameters();
        $parametersBuilded = [];
        foreach ($parameters as $p) {
            if ($p->getClass())
                $parametersBuilded[] = $this->get($p->getClass()->getName());
            else
                $parametersBuilded[] = $p->getDefaultValue();
        }
        return $parametersBuilded;
    }

}
