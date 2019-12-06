<?php

namespace App\Article\Application\Service;

use App\Article\Application\Service\Dto\ParkingView;
use App\Article\Application\Service\Response\EditResponse;
use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\Service\ArticleFinder;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Model\Article\Service\Finder\FindById;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use Framework\Session\FlashMessage;
use Framework\Session\SessionManager;
use Framework\Validator\AbstractFormValidator;

/**
 * This application service is responsible
 * to return a well form EditRespon to the controller
 *
 * @author mochiwa
 */
class EditArticleApplication {

    private $editArticleService;
    private $validator;
    private $session;
    private $finder;
    public function __construct(ArticleFinder $finder,EditArticleService $editArticleService, AbstractFormValidator $validator, SessionManager $session) {
        $this->finder=$finder;
        $this->editArticleService = $editArticleService;
        $this->validator = $validator;
        $this->session = $session;
    }

    /**
     * Responsible to return a Edit response for the admin controller.
     * Response possible:
     *  -NotFound : when the article to edit not found in repository
     *  -Not edited: when the post array is empty
     *  -not edited with errors: when the post array contain errors (from form or  domain)
     *  -edited : when no error occurs and article has been updated
     * @param string $id
     * @param array $post
     * @return EditResponse
     */
    public function __invoke(string $id, array $post): EditResponse {
        $articleFromDomain = $this->finder->findArticles(FindById::fromStringID($id))->getFirst();

        if(!$articleFromDomain) {
            return EditResponse::notFound();
        }
        elseif(empty($post)){
            return EditResponse::of(ParkingView::fromDomainResponse($articleFromDomain));
        }elseif (!$this->validator->validate($post)) {
            return EditResponse::of(ParkingView::fromDomainResponse($articleFromDomain), $this->validator->getErrors());
        }
        
        try {
            $domainResponse = $this->editArticleService->execute(EditArticleRequest::fromArray($id, $post));
            $this->session->setFlash(FlashMessage::success('The article "' . $domainResponse->title()->valueToString() . '" has been updated !'));
            return EditResponse::edited(ParkingView::fromDomainResponse($domainResponse));
        } catch (ArticleException $ex) {
            return EditResponse::of(ParkingView::fromDomainResponse($articleFromDomain), [$ex->field() => [$ex->field()]]);
        }
    }



}
