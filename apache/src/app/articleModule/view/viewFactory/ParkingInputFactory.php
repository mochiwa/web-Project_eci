<?php

namespace App\Article\view\ViewFactory;

use Framework\Html\Factory\DefaultInputFactory;
use Framework\Html\Input;

/**
 * Description of ParkingInputFactory
 *
 * @author mochiwa
 */
class ParkingInputFactory extends DefaultInputFactory{
    
    
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

}
