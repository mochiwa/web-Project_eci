<?php

namespace App\Identity\view\Factory;

use Framework\Html\Factory\DefaultInputFactory;
use Framework\Html\Input;
use Framework\Html\TextArea;

/**
 * Description of UserInputFactory
 *
 * @author mochiwa
 */
class UserInputFactory extends DefaultInputFactory{
    
    
    public function email($name): Input {
        return parent::email($name)
                ->addStyle('form__input');
    }

    public function file($name): Input {
        return parent::file($name)
                ->addStyle('form__input');
    }

    public function password($name): Input {
        return parent::password($name)
                ->addStyle('form__input');
    }

    public function text($name): Input {
        return parent::text($name)
                ->addStyle('form__input');
    }
    
    public function textArea($name): TextArea {
        return parent::textArea($name)
                ->addStyle('form__input');
    }

}
