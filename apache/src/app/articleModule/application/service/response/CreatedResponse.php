<?php

namespace App\Article\Application\Service\Response;

use App\Article\Application\Service\Dto\ParkingView;

/**
 * Description of CreateResponse
 *
 * @author mochiwa
 */
class CreatedResponse extends AbstractApplicationResponse{
    private $formData;
    public function __construct(array $errors = [], ParkingView $formData=null) {
        parent::__construct($errors);
        $this->formData=$formData;
    }
    
    public function getFormData(): array
    {
        if($this->formData===null){
            return [];
        }
        return $this->formData->toForm();
    }
}
