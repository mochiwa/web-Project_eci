
<section class="block">
    
    <?php
        $box = new \App\Article\view\ViewFactory\FlashBox($session->flash());
        echo $box->toHtml();
    ?>
    
   
    
<h1 class=""> Article management </h1>
<a class="button button table__button" href="<?= $router->generateURL('parking.admin.create')?>">Create a new article</a>
    
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
        <tr class="table-article <?= $key%2!==0 ? 'table-article--odd' : '' ?>">
            <td class="table-article__item table-article__item-title"><?= $article->getTitle() ?></td>
            <td class="table-article__item table-article__item-date " ><?= $article->getCreationDate() ?></td>
            <td class="table-article__item table-article__item-date " ><?= $article->getLastUpdateDate()?></td>
            <td lass="table-article__item">
                <a class="button table__button" href="<?= $router->generateURL('parking.admin.edit',['id'=>$article->getId()]) ?>">Edit</a>
                <a class="button table__button" href="<?= $router->generateURL('parking.admin.delete',['id'=>$article->getId()]) ?>">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
    
    <div>
        <?php for($i=1;$i<=$pageCount;$i++){
            echo '<a href="'.$router->generateURL('parking.admin.index-page',['page'=>$i]).'">'.$i.'</a>';
            
        } ?>
    </div>
    
</section>
