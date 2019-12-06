
<?php
use App\Article\view\ViewFactory\FormBuilder;

$form= FormBuilder::createParkingForm(
        $router->generateURL('parking.admin.edit',['action'=>'edit','id'=>$article->getId()]),
        $errors ?? [], $article->toForm() ?? [],
        $router->generateURL('parking.admin.index'));
?>


<section class="block form">
    <h1 class="form__title">Create a parking</h1>
    <?= $form->toHtml() ?>
</section>