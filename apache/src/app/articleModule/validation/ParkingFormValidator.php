<?php
namespace App\Article\Validation;

/**
 * Description of CreateParkingValidator
 *
 * @author mochiwa
 */
class ParkingFormValidator extends \Framework\Validator\AbstractFormValidator {
    
    public function __construct(array $formData) {
        parent::__construct($formData);
    }
    
    public function initRules() {
        $this->validator->rule('required',['title','picture','city','place','description']);
        
        $this->validator->rule('lengthBetween','title',3,50);
        $this->validator->rule('integer','place');
    }

}
