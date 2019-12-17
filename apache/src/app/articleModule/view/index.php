<?php

use App\Article\view\ViewFactory\PaginationFactory;
use Framework\Html\Pagination;

?>

    <h2 class="block__title block__title-center"> Our parking places</h2>
    
    <div class="container container-center container-article_preview">
        
        <?php foreach ($articles as $article) { ?>
        <article class="block article article_preview">
            <header class="article_preview-header">
                <h2 class="article__title"><a href="<?= $router->generateURL('article.selected',['action'=>'show','id'=>$article->getId()]) ?>"><?= $article->getTitle() ?></a></h2>
                <img class="article__picture" src="../upload/article/<?= $article->getPicture() ?>">
            </header>
            <div class="article_preview-main">
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

            <footer class="article_preview-footer">
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

