<?php

use App\Article\view\ViewFactory\FormFactory;

$form= FormFactory::createParkingForm($router->generateURL('parking.admin.create'),
        $errors ?? [], $values ?? [],$router->generateURL('parking.admin'));
?>


<section class="block form">
    <h1 class="form__title">Create a parking</h1>
    <?= $form->toHtml() ?>
</section>