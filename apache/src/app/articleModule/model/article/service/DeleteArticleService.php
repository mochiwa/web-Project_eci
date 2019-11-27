<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Article\Model\Article\Service;

use App\Article\Model\Article\ArticleId;
use App\Article\Model\Article\IArticleRepository;
use App\Article\Model\Article\Service\Request\DeleteArticleRequest;

/**
 * Description of DeleteArticleService
 *
 * @author mochiwa
 */
class DeleteArticleService {
    private $repository;
    
    public function __construct(IArticleRepository $repository) {
        $this->repository=$repository;
    }
    
    public function execute(DeleteArticleRequest $request){
        $id=ArticleId::of($request->getArticleId());
        $this->repository->removeArticle($id);
    }
}
