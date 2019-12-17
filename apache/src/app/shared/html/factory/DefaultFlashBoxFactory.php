<?php

namespace App\Shared\Html\Factory;

use Framework\Html\Factory\AbstractFlashBoxFactory;
use Framework\Html\FlashBox;
use Framework\Session\FlashMessage;

/**
 * Description of DefaultFlashBoxFactory
 *
 * @author mochiwa
 */
class DefaultFlashBoxFactory extends AbstractFlashBoxFactory{
    private $message;
    public function __construct(FlashMessage $message) {
        $this->message=$message;
    }
       
    public static function of(FlashMessage $message):FlashBox
    {
        $factory=new self($message);
        return new FlashBox($factory);
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