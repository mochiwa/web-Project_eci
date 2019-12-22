<?php

namespace App\Article\Application;

use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Response\ArticleApplicationResponse;

/**
 * Description of AbstractArticleApplication
 *
 * @author mochiwa
 */
class AbstractArticleApplication {

    /**
     * @var ParkingPOCO
     */
    protected $parkingPOCO;

    /**
     * @var array 
     */
    protected $errors;
    
    public function __construct() {
        $this->parkingPOCO = null;
        $this->errors = [];
    }

        /**
     * Return a ArticleApplicationResponse with error if error application
     * has error and with parking poco if the application has a articlePoco
     * @return ArticleApplicationResponse
     */
    protected function buildResponse(): ArticleApplicationResponse {
        $response = ArticleApplicationResponse::of();
        if (!empty($this->errors)) {
            $response->withErrors($this->errors);
        }
        if (isset($this->parkingPOCO)) {
            $response->withArticle($this->parkingPOCO);
        }
        return $response;
    }

}
