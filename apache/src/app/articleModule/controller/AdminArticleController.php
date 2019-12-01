<?php

namespace App\Article\Controller;

use App\Article\Application\Service\CreateArticleApplication;
use App\Article\Application\Service\DeleteArticleApplication;
use App\Article\Application\Service\EditArticleApplication;
use App\Article\Application\Service\FindArticleApplication;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\GettingArticleService;
use App\Article\Model\Article\Service\Request\GettingSingleArticleByIdRequest;
use App\Article\Model\Article\Service\Response\ArticleViewResponse;
use App\Article\Validation\ParkingEditFormValidator;
use App\Article\Validation\ParkingFormValidator;
use Exception;
use Framework\FileManager\FileUploader;
use Framework\Renderer\IViewBuilder;
use Framework\Session\SessionManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * A controller for administration
 *
 * @author mochiwa
 */
class AdminArticleController {

    private $viewBuilder;
    private $repository;
    private $session;
    private $uploader;

    function __construct(IViewBuilder $viewBuilder, IArticleRepository $repository, SessionManager $session, FileUploader $uploader) {
        $this->viewBuilder = $viewBuilder;
        $this->repository = $repository;
        $this->session = $session;
        $this->uploader = $uploader;
        $this->viewBuilder->addGlobal('session', $this->session);
    }

    public function __invoke(RequestInterface $request) {
        if (strpos($request->getRequestTarget(), 'create')) 
        {
            if($request->getMethod()==='POST'){
                return $this->createArticleProcess($request);
            }else{
                return $this->createArticle();
            }
        } else if (strpos($request->getRequestTarget(), 'edit')) {
            if($request->getMethod()==='POST'){
                return $this->editArticleProcess($request);
            }else{
                return $this->editArticle($request->getAttribute('id'));
            }
        } else if (strpos($request->getRequestTarget(), 'delete')) {
            return $this->deleteArticle($request->getAttribute('id'));
        }
        return $this->index();
    }

    /**
     * The main page to manage all article
     * @return Response
     */
    private function index() {
        $response = new Response();
        $data = [];
        foreach ($this->repository->All() as $article) {
            $data[] = new ArticleViewResponse($article);
        }
        $response->getBody()->write($this->viewBuilder->build('@article/index', ['data' => $data]));
        return $response;
    }

    /**
     * return the web page with the creation article form
     * @return ResponseInterface
     */
    private function createArticle(): ResponseInterface {
        $response = new Response(200);
        $response->getBody()->write($this->viewBuilder->build('@article/createArticle'));
        return $response;
    }
    
    /**
     * Responsible to assure the Post process of an article when it made.
     * If no errors are detected during the process the response will be redirected
     * to the admin index with a flash message accessible from the session.
     * When one or many errors occur then redirect to the form with the error list
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    private function createArticleProcess(RequestInterface $request): ResponseInterface {
        $post = $request->getParsedBody();
        $post['picture'] = $this->extractPictureFromRequest($request,'picture');
         
        $service = new CreateArticleApplication($this->repository, new ParkingFormValidator(), $this->uploader,$this->session);
        $response = $service->execute($post);

        if ($response->hasErrors()) {
            return $this->responseWithErrors('@article/createArticle', ['errors'=> $response->getErrors()]);
        }
        return $this->redirectToIndex();
    }
    
    /**
     * Extract a file path from a request, if file not found then return empty string
     * @param RequestInterface $request
     * @return string
     */
    private function extractPictureFromRequest(RequestInterface $request,string $field): string {
        try {
            return $request->getUploadedFiles()[$field]->getStream()->getMetadata('uri');
        } catch (Exception $ex) {
            
        }
        return '';
    }
    
    
    /**
     * Return a response that redirect to the admin index
     * @param int $code the status code 200 by default
     * @return ResponseInterface
     */
    private function redirectToIndex(int $code=200) : ResponseInterface
    {
        $response = new Response($code);
        return $response->withHeader('Location', '/parking/admin');
    }
    
    
    
   
    
    /**
     * Return the form with the data from the article
     * when error occur it's return to the index
     * @param string $id
     * @return Response
     */
    private function editArticle(string $id) {
        
        $service=new FindArticleApplication($this->repository,$this->session);
        $response=$service->execute($id);
        if($response->hasErrors())
        {
            return $this->redirectToIndex(400);
        }
        return new Response(200, [], $this->viewBuilder->build('@article/editArticle', ['article'=>$response->getArticle()]));
    }
    
    private function editArticleProcess(RequestInterface $request) {
        $post = $request->getParsedBody();
        $post['id'] = $request->getAttribute('id');
        
        $service = new EditArticleApplication($this->repository, new ParkingEditFormValidator(),$this->session);
        $response = $service->execute($post);
        
        if ($response->hasErrors()) {
            return $this->responseWithErrors('@article/editArticle', ['errors' => $response->getErrors(),'article'=>$response->getArticle()]);
        }
        return $this->redirectToIndex();
    }

    /**
     * Delete an article
     * @param string $articleId
     * @return ResponseInterface
     */
    private function deleteArticle(string $articleId): ResponseInterface {
        
        $service=new DeleteArticleApplication($this->repository,$this->session);
        $response=$service->execute($articleId);
        if($response->hasErrors())
        {
            return $this->redirectToIndex(400);
        }
        return $this->redirectToIndex();
    }

    /**
     * Return a response with one or many errors
     * @param string $view
     * @param type $errors
     * @param int $status
     * @return Response
     */
    private function responseWithErrors(string $view, $errors, int $status = 400) {
        $response = new Response($status);
        $response->getBody()->write($this->viewBuilder->build($view,  $errors ));
        return $response;
    }

}
