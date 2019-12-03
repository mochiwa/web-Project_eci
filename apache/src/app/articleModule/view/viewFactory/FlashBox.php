<?php

namespace App\Article\view\ViewFactory;

use Framework\Html\HtmlTag;
use Framework\Session\FlashMessage;

/**
 * Description of FlashBox
 *
 * @author mochiwa
 */
class FlashBox extends HtmlTag{
    
    public function __construct(FlashMessage $message) {
        parent::__construct('div');
        $this->addStyle('flashBox');
        $this->addStyle($message->getIsError() ? 'flashBox-error' : 'flashBox-success');
        $this->addChild((HtmlTag::make('p')->addStyle('flashBox__message')->addText($message->getMessage())));
    }
}
