<?php

namespace App\Article\Application;

use App\Article\Application\AbstractArticleApplication;
use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Request\CreateArticleRequest;
use App\Article\Application\Response\ArticleApplicationResponse;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\Attribute;
use App\Article\Model\Article\Picture;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\Request\CreateArticleRequest as DomainRequest;
use App\Article\Model\Article\Title;
use Framework\FileManager\FileException;
use Framework\FileManager\FileUploader;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;
use Framework\Validator\ValidatorException;
use InvalidArgumentException;


/**
 * Description of CreateArticleApplication
 *
 * @author mochiwa
 */
class CreateArticleApplication extends AbstractArticleApplication{
    
    /**
     * @var AbstractFormValidator 
     */
    private $validator;
    
    /**
     * @var CreateArticleService 
     */
    private $articleProvider;
    
    /**
     * @var type FileUploader
     */
    private $fileUploader;
    /**
     *
     * @var SessionManager 
     */
    private $sessionManager;
    
    public function __construct(AbstractFormValidator $validator,
            CreateArticleService $articleProvider,
            FileUploader $fileUploader,
            SessionManager $sessionManager) {
        parent::__construct();
        $this->validator = $validator;
        $this->articleProvider=$articleProvider;
        $this->fileUploader=$fileUploader;
        $this->sessionManager=$sessionManager;
    }

    
    
    public function __invoke(CreateArticleRequest $request) : ArticleApplicationResponse {
        $dataFromForm=$request->toAssociativeArray();
        $this->parkingPOCO=ParkingPOCO::fromAssociativeArray($dataFromForm);
        try{
            $this->validator->validateOrThrow($dataFromForm);
            $article=call_user_func($this->articleProvider,$this->buildDomainRequest($dataFromForm));
            $this->fileUploader->uploadToDefault($dataFromForm['picture'], $article->picture()->name());
            $this->parkingPOCO= ParkingPOCO::of($article);
            $this->sessionManager->setFlash(FlashMessage::success('The Article '.$this->parkingPOCO->getTitle().' has been created successfully'));
            
        } catch (ValidatorException $ex) {
            $this->errors=$ex->getErrors();
        } catch (InvalidArgumentException $ex){
            $this->errors=['valueObject'=>$ex->getMessage()];
        }catch(ArticleException $ex){
            $this->errors=['domain'=>$ex->getMessage()];
        }catch (FileException $ex){
            $this->errors=['file'=>$ex->getMessage()];
        }finally{
            return $this->buildResponse();
        }
    }
    
    
    private function buildDomainRequest(array $form) : DomainRequest{
        return DomainRequest::of(Title::of($form['title']),
            Picture::of($this->fileUploader->defaultDirectoryLocalPath(),$form['picture']),
            [Attribute::of('city', $form['city']),
            Attribute::of('place', $form['place']),
            Attribute::of('name', $form['name'])],
            $form['description']);
    }
    
    
}
