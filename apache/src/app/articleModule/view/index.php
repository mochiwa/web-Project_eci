<?php

use App\Article\view\ViewFactory\PaginationFactory;
use Framework\Html\Pagination;

?>

<section class="block">
    <h1 class="block__title block__title-center"> Our parking places</h1>
    <div class="container container-center">
        <?php foreach ($articles as $article) { ?>
        <article class="block article article-preview">
            <header class="article-preview-header">
                <h2 class="article__title"><a href="<?= $router->generateURL('article.selected',['action'=>'show','id'=>$article->getId()]) ?>"><?= $article->getTitle() ?></a></h2>
                <img class="article-preview__thumbnail" src="../upload/article/<?= $article->getPicture() ?>">
            </header>
            <div class="article-preview-main">

               <table class="attribute_map">
                <?php foreach ($article->getAttributes() as $key => $value){?>
                       <tr class="attribute_map-element">
                           <td class="attribute_map-element__key"><?=$key?></td>
                           <td class="attribute_map-element__value"><?=$value?></td>
                       </tr>

                <?php } ?>
               </table>

                <p class="article__description"><?= $article->getDescription() ?></p>
            </div>

            <footer class="article-preview-footer">
                <a class="button" href="<?= $router->generateURL('article.selected',['action'=>'show','id'=>$article->getId()]) ?>"> more </a>
            </footer>
        </article>
        <?php } ?>
    </div>
    <div class="container container-center">
        <?php
        $paginator= new Pagination(new PaginationFactory($router,'parking.page'),$pagination);
        echo $paginator->toHtml();
        ?>
    </div>
</section>

