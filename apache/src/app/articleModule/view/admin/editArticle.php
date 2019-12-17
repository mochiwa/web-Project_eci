
<?php

use App\Article\view\ViewFactory\FormBuilder;

$form= FormBuilder::editParkingForm(
        $router->generateURL('parking.admin.article',['action'=>'edit','id'=>$article->getId()]),
        $errors ?? [], $article->toForm() ?? [],
        $router->generateURL('parking.admin'));
?>


<section class="block form">
    <h1 class="form__title">Create a parking</h1>
    <?= $form->toHtml() ?>
</section>