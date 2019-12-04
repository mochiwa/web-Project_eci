<?php

namespace App\Article\Application\Service\Response;

/**
 * Description of FindResponse
 *
 * @author mochiwa
 */
class FindResponse extends AbstractApplicationResponse{
    private $dataFound;
    public function __construct(array $errors=[],array $dataFound) {
        parent::__construct($errors);
        $this->dataFound=$dataFound;
    }
    
    public function getDataFound():array {
        return $this->dataFound;
    }
    
    public function getFirst()
    {
        return $this->dataFound ? $this->dataFound[0] : null; 
    }


}
