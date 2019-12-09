<?php

namespace App\Identity\view\Factory;
use Framework\Html\Factory\DefaultFormFactory;

/**
 * Description of UserFormFactory
 *
 * @author mochiwa
 */
class UserFormFactory extends DefaultFormFactory{
    
    public function __construct(string $action = '#', string $method = 'POST', string $id = 'form') {
        parent::__construct($action, $method, $id);
    }

    public function action(): \Framework\Html\Attribute {
        return parent::action();
    }

    public function cancel(string $text, string $target): \Framework\Html\HtmlTag {
        return parent::cancel($text, $target);
    }

    public function id(): string {
        return parent::id();
    }

    public function label(string $text, string $for): \Framework\Html\HtmlTag {
        return parent::label($text, $for);
    }

    public function method(): \Framework\Html\Attribute {
        return parent::method();
    }

    public function sectionButtons(): ?\Framework\Html\HtmlTag {
        $div=parent::sectionButtons();
        $div->addStyle('form-section')
            ->addStyle('form-section-buttons');
        return $div;
    }

    public function sectionErrors(array $errors = array()): ?\Framework\Html\HtmlTag {
        return parent::sectionErrors($errors);
    }

    public function sectionFields(): ?\Framework\Html\HtmlTag {
        $div=parent::sectionFields();
        $div->addStyle('form-section')
            ->addStyle('form-section-fields');
        return $div;
    }

    public function submit(string $text): \Framework\Html\Input {
        $submit= parent::submit($text);
        $submit->addStyle('button');
        return $submit;
    }

}
