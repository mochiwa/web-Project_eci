<?php

namespace App\Article\Application;

use App\Article\Application\Poco\ParkingPOCO;
use App\Article\Application\Request\DeleteArticleRequest;
use App\Article\Application\Response\ArticleApplicationResponse;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\Service\DeleteArticleService;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest as DomainRequest;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use InvalidArgumentException;

/**
 * Description of DeleteArticleApplication
 *
 * @author mochiwa
 */
class DeleteArticleApplication extends AbstractArticleApplication {
    /**
     * @var DeleteArticleService
     */
    private $domainService;
    
    /**
     * @var SessionManager 
     */
    private $session;
    
    public function __construct(DeleteArticleService $domainService, SessionManager $session) {
        $this->domainService = $domainService;
        $this->session=$session;
    }
    
    
    /**
     * Delete an article from the repository, if the article already delete or not found in repository,
     * return a ApplicationResponse with error , and set a flash message to explain what appending.
     * If no error then the article will be deleted from the domain and a ParkingPOCO of
     * the deleted article will be returned with a success flash message with  undo link
     * @param DeleteArticleRequest $request
     * @return ArticleApplicationResponse
     */
    public function __invoke(DeleteArticleRequest $request): ArticleApplicationResponse{
        try{
            $articleId=ArticleId::of($request->getArticleId());
            $articleDeleted=call_user_func($this->domainService, DomainRequest::of($articleId));
            $this->parkingPOCO= ParkingPOCO::of($articleDeleted);
            $this->session->setFlash(FlashMessage::success('Article '.$this->parkingPOCO->getTitle().' has been successfuly deleted <a href="#">undo</a>'));
        } catch (InvalidArgumentException $ex) {
            $this->session->setFlash(FlashMessage::error($ex->getMessage()));
            $this->errors=['domain'=>'an error occur during the deleting process'];
        }catch(ArticleException $ex){
            $this->session->setFlash(FlashMessage::error($ex->getMessage()));
            $this->errors=['domain'=>'The article is already deleted'];
        }finally{
            return $this->buildResponse();
        }
    }
}
