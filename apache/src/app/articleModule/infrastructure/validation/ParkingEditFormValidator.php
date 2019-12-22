<?php

namespace App\Article\Infrastructure\Validation;

use Framework\Validator\AbstractFormValidator;

/**
 * Description of ParkingEditFormValidator
 *
 * @author mochiwa
 */
class ParkingEditFormValidator extends AbstractFormValidator {
    public function __construct(array $formData=[]) {
        parent::__construct($formData);
    }
    
    public function initRules() {
        $this->validator->rule('required',['title','city','place','description']);
        
        $this->validator->rule('lengthBetween','title',3,50);
        $this->validator->rule('integer','place');
    }
}
