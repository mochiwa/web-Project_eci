<?php
namespace App\Article\Controller;

use App\Article\Model\Article\ArticleException;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\CreateArticleService;
use App\Article\Model\Article\Service\DeleteArticleService;
use App\Article\Model\Article\Service\Request\CreateArticleRequest;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest;
use App\Article\Model\Article\Service\Response\ArticleViewResponse;
use App\Article\Validation\ParkingFormValidator;
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
    
    function __construct(IViewBuilder $viewBuilder, IArticleRepository $repository, SessionManager $session) {
        $this->viewBuilder=$viewBuilder;
        $this->repository=$repository;
        $this->session=$session;
        $this->viewBuilder->addGlobal('session', $this->session);
    }
    
    public function __invoke(RequestInterface $request) {
        if(strpos($request->getRequestTarget(), 'create'))
        {
            return $this->createArticle($request);
        }
        else if(strpos($request->getRequestTarget(), 'edit'))
        {
            return $this->editArticle($request->getAttribute('id'));
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
       $response=new Response(200);
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
           return $this->createArticleProcess($request->getParsedBody());
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
        if($validator->isValid()){
            try {
                $request = CreateArticleRequest::fromArray($post);
                $service = new CreateArticleService($this->repository);
                $service->execute($request);
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

    private function editArticle(string $articleId)
    {
        
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
