<?php

namespace App\Article\view\ViewFactory;

use Framework\Html\DefaultFormFactory;
use Framework\Html\HtmlTag;
use Framework\Html\Input;

/**
 * Description of ParkingFormFactory
 *
 * @author mochiwa
 */
class ParkingFormFactory extends DefaultFormFactory {
    
    public function cancel(string $text, string $target): HtmlTag {
        $a=parent::cancel($text, $target);
        return $a;
    }


    public function label(string $text, string $for): HtmlTag {
        $label= parent::label($text, $for);
        return $label;
    }


    public function sectionButtons(): ?HtmlTag {
        $div=parent::sectionButtons();
        return $div;
    }

    public function sectionErrors(array $errors=[]): ?HtmlTag {
        $div=parent::sectionErrors($errors);
        return $div;
    }

    public function sectionFields(): ?HtmlTag {
        $div=parent::sectionFields();
        return $div;
    }

    public function submit(string $text): Input {
        $submit= parent::submit($text);
        $submit->addStyle('button');
        return $submit;
    }

}
