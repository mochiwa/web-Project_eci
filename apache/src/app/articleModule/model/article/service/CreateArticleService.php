<?php
namespace App\Article\Model\Article\Service;

/**
 * Description of CreateArticleService
 *
 * @author mochiwa
 */
class CreateArticleService {
   
    
    public function execute(CreateArticleRequest $request)
    {
        $article=Article::newArticle($this->articleRepository->nextId(),
                $request->getTitle(),
                $request->getPicture(),
                $request->getAttributes(),
                $request->getDescription());
    }
}
