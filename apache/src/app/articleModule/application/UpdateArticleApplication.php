<?php

namespace App\Article\Application;

use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Request\ArticleRequest;
use App\Article\Application\Response\ArticleApplicationResponse;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\EntityNotFoundException;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use App\Article\Model\Article\Title;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;
use Framework\Validator\ValidatorException;

/**
 * Description of UpdateArticleApplication
 *
 * @author mochiwa
 */
class UpdateArticleApplication extends AbstractArticleApplication{
    /**
     * @var AbstractFormValidator 
     */
    private $validator;
    
    /**
     * @var EditArticleService 
     */
    private $domainService;
    
    
    private $session;
    
    public function __construct(AbstractFormValidator $validator, EditArticleService $domainService,SessionManager $session) {
        parent::__construct();
        $this->validator = $validator;
        $this->domainService=$domainService;
        $this->session=$session;
    }

    
    public function __invoke(ArticleRequest $request) : ArticleApplicationResponse{
        $dataFromPost=$request->toAssociativeArray();
        try{
            $this->parkingPOCO= ParkingPOCO::fromAssociativeArray($dataFromPost);
            $this->validator->validateOrThrow($dataFromPost);
           
            $article=call_user_func($this->domainService,$this->buildDomainResponse($request));
            $this->parkingPOCO= ParkingPOCO::of($article);
            
        
            $this->session->setFlash(FlashMessage::success('The article '.$this->parkingPOCO->getTitle().' has been successfuly updated'));
        }catch(ValidatorException $ex) {
            $this->errors=$ex->getErrors();
        }catch(EntityNotFoundException $ex){
            $this->errors=['domain'=>$ex->getMessage()];
        }catch(ArticleException $ex){
            $this->errors=['domain'=>'An article with this title already exist'];
        }finally {
            return $this->buildResponse();
        }
    }
    
    private function buildDomainResponse(ArticleRequest $request) : EditArticleRequest{
        return EditArticleRequest::of(
            ArticleId::of($request->getArticleId()),
            Title::of($request->getTitle()),
            Picture::of($request->getPictureTmpPath()),
            [Attribute::of('city',$request->getCity()),
                Attribute::of('place',$request->getPlace()),
                Attribute::of('name',$request->getName())],
            $request->getDescription());
    }
    
}
