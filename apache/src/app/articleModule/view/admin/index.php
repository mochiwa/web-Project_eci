<?php

use App\Article\view\ViewFactory\FlashBoxFactory;
use App\Article\view\ViewFactory\PaginationFactory;
use Framework\Html\FlashBox;
use Framework\Html\Pagination;
?>

<section class="block">
    <?php
        $box=new FlashBox(new FlashBoxFactory($session->flash()));
        echo $box->toHtml();
    ?>
    
   
    
<h1 class="block__title block__title-center"> Article management </h1>
<a class="button button table__button" href="<?= $router->generateURL('parking.admin',['action'=>'create'])?>">Create a new article</a>
    
<table class="table">
    <thead>
        <tr class="table-header">
           <th class="table-header__item">Article</th>
           <th class="table-header__item table-header__item--hidden">Publish date</th>
           <th class="table-header__item table-header__item--hidden">Last update</th>
           <th class="table-header__item">Command</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($articles as $key => $article) {?>
        <tr class="table-article <?= $key%2===0 ? 'table-article--odd' : '' ?>">
            <td class="table-article__item table-article__item-title"><?= $article->getTitle() ?></td>
            <td class="table-article__item table-article__item-date "><?= $article->getCreationDate() ?></td>
            <td class="table-article__item table-article__item-date "><?= $article->getLastUpdateDate()?></td>
            <td lass="table-article__item">
                <a class="button table__button" href="<?= $router->generateURL('parking.admin.article',['action'=>'edit','id'=>$article->getId()]) ?>">Edit</a>
                <a class="button table__button" href="<?= $router->generateURL('parking.admin.article',['action'=>'delete','id'=>$article->getId()]) ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

    <div class="container container-center">
        <?php
        $paginator= new Pagination(new PaginationFactory($router,'parking.admin.page'),$pagination);
        echo $paginator->toHtml();
        ?>
    </div>
    
</section>
