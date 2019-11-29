<?php
namespace App\Article\Controller;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\DeleteArticleService;
use App\Article\Model\Article\Service\EditArticleService;
use App\Article\Model\Article\Service\GettingArticleService;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest;
use App\Article\Model\Article\Service\Request\EditArticleRequest;
use App\Article\Model\Article\Service\Request\GettingSingleArticleByIdRequest;
use App\Article\Model\Article\Service\Response\ArticleViewResponse;
use App\Article\Validation\ParkingFormValidator;
use Framework\FileManager\FileUploader;
use Framework\Renderer\IViewBuilder;
use Framework\Session\SessionManager;
use GuzzleHttp\Psr7\Request;
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
        $this->viewBuilder=$viewBuilder;
        $this->repository=$repository;
        $this->session=$session;
        $this->uploader=$uploader;
        $this->viewBuilder->addGlobal('session', $this->session);
    }
    
    public function __invoke(RequestInterface $request) {
        if(strpos($request->getRequestTarget(), 'create'))
        {
            return $this->createArticle($request);
        }
        else if(strpos($request->getRequestTarget(), 'edit'))
        {
            return $this->editArticle($request);
        }
        else if(strpos($request->getRequestTarget(), 'delete'))
        {
            return $this->deleteArticle($request->getAttribute('id'));
        }
        return $this->index();
    }
    
    /**
     * The main page to manage all article
     * @return Response
     */
    private function index(){
       $response=new Response();
       $data =[];
       foreach ($this->repository->All() as $article) {
           $data[]=new ArticleViewResponse($article);
       }
       $response->getBody()->write($this->viewBuilder->build('@article/index',['data'=>$data]));
       return $response;
    }
    
    private function createArticle(RequestInterface $request): ResponseInterface
    {
       $response=new Response(200);
       if($request->getMethod()==='POST')
       {
           $post=$request->getParsedBody();
           $post['picture']=$request->getUploadedFiles()['picture']->getStream()->getMetadata('uri');
           return $this->createArticleProcess($post);
       }
       $response->getBody()->write($this->viewBuilder->build('@article/createArticle'));
       return $response;
    }
    
    /**
     * Deal with the use case 'create an article'
     * If no errors are detected then redirect to the admin index
     * else redirect to the form with errors in a array
     * @param array $post
     * @return ResponseInterface
     */
    private function createArticleProcess(array $post) : ResponseInterface {
        $response=new Response();
        $validator = new ParkingFormValidator($post);
        $errors=[];
        
        $applicationService = new Service\CreateArticleService;
        $applicationService($post);
        
        if($validator->isValid()){
            try {
                $request = CreateArticleRequest::fromArray($post);
                $service = new CreateArticleService($this->repository);
                $article=$service->execute($request);
                $this->uploader->uploadToDefault($post['picture'], $article->picture()->path());
                
                $this->session->set('flashMessage',['isError'=>false,'message'=>"One article has been appended !"]);
                $response=$response->withHeader('Location', '/parking/admin');
            } catch (ArticleException $e) {
                $errors[$e->field()]=[$e->getMessage()];
            }
        }
        else{
           $errors=$validator->getErrors();
        }
        
        if(empty($errors)){
            return $response->withHeader('Location', '/parking/admin');
        }
        return $this->responseWithErrors('@article/createArticle', $errors);
    }
    
    
    
    
    

    private function editArticle(Request $request)
    {
        $response=new Response();
        if($request->getMethod()==='POST')
        {
            return $this->editArticleProcess($request);
        }
        else
        {
            try{
                $request=new GettingSingleArticleByIdRequest($request->getAttribute('id'));
                $service=new GettingArticleService($this->repository);
                $article=$service->execute($request);
                $response->getBody()->write($this->viewBuilder->build('@article/editArticle', compact('article')));
            } catch (\Exception $e) {
                $this->session->set('flashMessage',['isError'=>true,'message'=>$e->getMessage()]);
                return (new Response(400))->withHeader('Location', '/parking/admin');
            }
        }
        return $response;
    }
    
    private function editArticleProcess(RequestInterface $request) {
        $post=$request->getParsedBody();
        $post['id']=$request->getAttribute('id');
        try {
            $post = $request->getParsedBody();
            $post['id'] = $request->getAttribute('id');
            $request = EditArticleRequest::fromArray($post);
            $service = new EditArticleService($this->repository);
            $service->exectue($request);
            $this->session->set('flashMessage', ['isError' => false, 'message' => 'The article has been updated !']);
            return (new Response(200))->withHeader('Location', '/parking/admin');
        } catch (\Exception $e) {
            $this->session->set('flashMessage', ['isError' => true, 'message' => $e->getMessage()]);
            return $response->getBody()->write($this->viewBuilder->build('@article/editArticle', ['errors' => $e->getMessage()]));
        }
    }

    /**
     * Delete an article
     * @param string $articleId
     * @return ResponseInterface
     */
    private function deleteArticle(string $articleId) : ResponseInterface
    {
        try {
            $service=new DeleteArticleService($this->repository);
            $service->execute(new DeleteArticleRequest($articleId));
            $this->session->set('flashMessage',['isError'=>false,'message'=>"One article has been deleted !"]);
        } catch (\Exception $ex) {
            $this->session->set('flashMessage',['isError'=>true,'message'=>"This article has been already deleted"]);
            return (new Response(400))->withHeader('Location', '/parking/admin');
        }
        return (new Response(200))->withHeader('Location', '/parking/admin');
    }
    
    
    /**
     * Return a response with one or many errors
     * @param string $view
     * @param type $errors
     * @param int $status
     * @return Response
     */
    private function responseWithErrors(string $view,$errors,int $status=406)
    {
        $response=new Response($status);
        $response->getBody()->write($this->viewBuilder->build($view,['errors'=>$errors]));
        return $response;
    }
}
