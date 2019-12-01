<?php

namespace App\htmlBuilder;

use Framework\HtmlBuilder\HtmlElement;
use Framework\Session\FlashMessage;

/**
 * Description of FlashBox
 *
 * @author mochiwa
 */
class FlashBox extends HtmlElement{
    
    public function __construct(FlashMessage $message) {
        parent::__construct('div');
        $this->addStyle('flashBox');
        $this->addStyle($message->getIsError() ? 'flashBox-error' : 'flashBox-success');
        $this->addChild((new HtmlElement('p'))->addStyle('flashBox__message')->setContent($message->getMessage()));
    }
}

