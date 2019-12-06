<?php

namespace App\Article\Application\Service\Response;

use App\Article\Application\Service\Dto\ParkingView;

/**
 * Description of FindResponse
 *
 * @author mochiwa
 */
class FindResponse extends AbstractApplicationResponse{
    private $dataFound;
    public function __construct(array $dataFound,array $errors=[]) {
        parent::__construct($errors);
        $this->dataFound=$dataFound;
    }
    
    public function getDataFound():array {
        return $this->dataFound;
    }
    
    public function getFirst() : ParkingView
    {
        return $this->dataFound ? $this->dataFound[0] : null; 
    }


}
