<div class="block article">
    <header class="article-header">
        <h2 class="article__title"><?= $article->getTitle() ?></h2>
        <img class="article__thumbnail" src="../upload/article/<?= $article->getPicture() ?>">
    </header>
    
    <div class="article-main">
        <section class="article-section article-section-information">
            <h3 class="article-section__title"> Informations </h3>
            <table class="attribute_map">
            <?php foreach ($article->getAttributes() as $key => $value){?>
                   <tr class="attribute_map-element">
                       <td class="attribute_map-element__key"><?=$key?></td>
                       <td class="attribute_map-element__value"><?=$value?></td>
                   </tr>

            <?php } ?>
           </table>
        </section>
        <section class="article-section article-section-description">
            <h3 class="article-section__title"> Description </h3>
            <p><?= $article->getDescription() ?></p>
        </section>
    </div>
    
    <div class="article-footer">
        
    </div>
        
    
</div>
         