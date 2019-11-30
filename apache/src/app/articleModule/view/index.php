
<section class="block">
    
    <div class="flashMessage-box">
        <p class="flashMessage-box__message"><?= $session->flash()['message']?></p>
    </div>
    
    
    <h1 class=""> Article management </h1>
    <a class="button" href="<?= $router->generateURL('parking.admin.create')?>">Create a new article</a>
    
<table>
    <tr>
       <th>Article</th>
       <th>Publish date</th>
       <th>Last update</th>
       <th>Command</th>
    </tr>
    <?php foreach($data as $article) {?>
    <tr>
        <td><?= $article->getTitle() ?></td>
        <td><?= $article->getCreationDate() ?></td>
        <td><?= $article->getLastUpdateDate() ?></td>
        <td>
            <a class="button" href="<?= $router->generateURL('parking.admin.edit',['id'=>$article->getId()]) ?>">Edit</a>
            <a class="button" href="<?= $router->generateURL('parking.admin.delete',['id'=>$article->getId()]) ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
    
</section>
