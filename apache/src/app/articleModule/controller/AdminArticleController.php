<?php

namespace App\Article\Controller;

use App\Article\Application\Service\CreateArticleApplication;
use App\Article\Application\Service\DeleteArticleApplication;
use App\Article\Application\Service\EditArticleApplication;
use App\Article\Application\Service\FindArticleApplication;
use App\Article\Application\Service\IndexArticleApplication;
use Exception;
use Framework\DependencyInjection\IContainer;
use Framework\Renderer\IViewBuilder;
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
    private $container;
    function __construct(IContainer $container) {
        $this->container=$container;
        $this->viewBuilder=$container->get(IViewBuilder::class);
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
            return $this->edit($request);
        } else if (strpos($request->getRequestTarget(), 'delete')) {
            return $this->deleteArticle($request->getAttribute('id'));
        }
        $page=$request->getAttribute('page');
        return $this->index($page ?? '1');
    }

    /**
     * The main page to manage all article
     * @return ResponseInterface
     */
    private function index(string $page) : ResponseInterface{
        $appService=$this->container->get(IndexArticleApplication::class);//new IndexArticleApplication($this->repository);
        $appResponse=$appService->execute($page);
        
        $httpResponse=new Response(200);
        $httpResponse->getBody()->write(
                $this->viewBuilder->build('@article/index',
                ['articles' => $appResponse->getArticles(),
                'pagination'=>$appResponse->getPagination()]));
        return $httpResponse;
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
     * If no errors are detected during the process, the response will be redirected
     * to the admin index with a flash message accessible from the session.
     * When one or many errors occur then redirect to the form with the error list
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    private function createArticleProcess(RequestInterface $request): ResponseInterface {
        $post = $request->getParsedBody();
        $post['picture'] = $this->extractPictureFromRequest($request,'picture');
         
        $service = $this->container->get(CreateArticleApplication::class);//new CreateArticleApplication($this->repository, new ParkingFormValidator(), $this->uploader,$this->session);
        $response = $service->execute($post);

        if ($response->hasErrors()) {
            return $this->responseWithErrors('@article/createArticle', ['errors'=> $response->getErrors(),'formData'=>$response->getFormData()]);
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
     * Return the form with the data from the article
     * when error occur it's return to the index
     * @param string $id
     * @return Response
     */
    

    /**
     * Manage the edit process
     * @see EditResponse
     * @see EditArticleApplication
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    private function edit(RequestInterface $request) : ResponseInterface
    {
        $post = $request->getParsedBody();
        $id=$request->getAttribute('id');
        $service = $this->container->get(EditArticleApplication::class);
        $response=$service($id,$post);
        if($response->isEdited() || $response->isArticleNotFound()){
            return $this->redirectToIndex();
        }
        return $this->responseWithErrors('@article/editArticle', ['errors' => $response->getErrors(),'article'=>$response->getArticle()]);
    }
     
    
    
    /**
     * Delete an article
     * @param string $articleId
     * @return ResponseInterface
     */
    private function deleteArticle(string $articleId): ResponseInterface {
        $service=$this->container->get(DeleteArticleApplication::class);//new DeleteArticleApplication($this->repository,$this->session);
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
    private function responseWithErrors(string $view, $errors, int $status = 400,string $cause=''):ResponseInterface {
        $response = new Response();
        $response->getBody()->write($this->viewBuilder->build($view,  $errors ));
        return $response->withStatus($status, $cause);
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
}
