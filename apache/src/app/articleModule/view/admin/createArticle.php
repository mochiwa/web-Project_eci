<?php
use App\Article\view\ViewFactory\FormBuilder;
$form= FormBuilder::createParkingForm(
        $router->generateURL('parking.admin',['action'=>'create']),
        $errors ?? [], isset($article) ? $article->toForm(): [],
        $router->generateURL('parking.admin'));
?>


<section class="block form">
    <h1 class="form__title">Create a parking</h1>
    <?= $form->toHtml() ?>
</section>