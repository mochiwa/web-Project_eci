<?php
use App\Article\view\ViewFactory\FormBuilder;
$form= FormBuilder::createParkingForm(
        $router->generateURL('parking.admin.create'),
        $errors ?? [], $formData ?? [],
        $router->generateURL('parking.admin.index'));
?>


<section class="block form">
    <h1 class="form__title">Create a parking</h1>
    <?= $form->toHtml() ?>
</section>