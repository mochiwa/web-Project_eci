<?php

namespace App\Article\view\ViewFactory;

use Framework\Html\Factory\AbstractFlashBoxFactory;
use Framework\Session\FlashMessage;

/**
 * Description of FlashBoxFactory
 *
 * @author mochiwa
 */
class FlashBoxFactory extends AbstractFlashBoxFactory{
    private $message;
    public function __construct(FlashMessage $message) {
        $this->message=$message;
    }

    
    public function getBoxStyle(): string {
        return $this->message->getIsError() ? 'flashBox flashBox-error' : 'flashBox flashBox-success';
    }

    public function getMessage(): string {
        return $this->message->getMessage();
    }

    public function getMessageStyle(): string {
        return 'flashBox__message';
    }

}
