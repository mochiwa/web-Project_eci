<?php
namespace Framework\Html\Factory;

use Framework\Html\Input;

/**
 * The basic input factory without special behavior
 * @author mochiwa
 */
class DefaultInputFactory extends AbstractInputFactory{
    
    public function email($name) :Input {
        return new Input($name, 'email');
    }

    public function file($name) :Input {
        return new Input($name, 'file');
    }

    public function password($name) :Input {
        return new Input($name, 'password');
    }

    public function text($name) :Input {
        return new Input($name, 'text');
    }

    public function build(string $type, string $name) :Input {
        return  call_user_func_array([$this,$type], [$name]);
    }

}
