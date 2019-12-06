<?php

namespace Framework\Html\Factory;

/**
 * Description of DefaultFlashBoxFactory
 *
 * @author mochiwa
 */
class DefaultFlashBoxFactory extends AbstractFlashBoxFactory{
    private $message;
    
    public function __construct($message='') {
        $this->message = $message;
    }

    
    public function getBoxStyle(): string {
        return '';
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getMessageStyle(): string {
        return '';
    }

}
