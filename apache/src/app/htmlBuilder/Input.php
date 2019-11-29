<?php

namespace App\htmlBuilder;

use Framework\HtmlBuilder\Input as FrameworkInput;

/**
 * This class is responsible to generate input for form for the application JohnCar
 */
class Input extends FrameworkInput{

    function __construct(string $name, string $type = 'text', string $id = '', string $value = '', string $placeHolder = '', bool $required = false) {
        parent::__construct($name, $type, $id, $value, $placeHolder, $required);
        $this->addStyle('form__item');
    }

    public static function text(string $name, string $placeHolder, bool $required = false,string $value='') {
        $input = new Input($name, 'text', '', $value, $placeHolder, $required);
        $input->addStyle('form__input');
        return $input;
    }

    public static function email(string $name, string $placeHolder, bool $required = false,string $value='') {
        $input = new Input($name, 'email', '', '', $placeHolder, $required);
        $input->addStyle('form__input');
        return $input;
    }

    public static function password(string $name, string $placeHolder, bool $required = false,string $value='') {
        $input = new Input($name, 'password', '', '', $placeHolder, $required);
        $input->addStyle('form__input');
        return $input;
    }

    public static function submit(string $name, string $textToPrint) {
        $input = new Input($name, 'submit', '', $textToPrint);
        $input->addStyle('button');
        $input->addStyle('form__button');
        return $input;
    }
    
    public static function file(string $name, string $placeHolder, bool $required = false,string $value='') {
        $input = new Input($name, 'file', '', $value, $placeHolder, $required);
       
        return $input;
    }

}

?>