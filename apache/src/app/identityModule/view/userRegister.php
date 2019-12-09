<?php

use App\Identity\view\Factory\UserFormFactory;
use App\Identity\view\Factory\UserInputFactory;
use Framework\Html\Factory\DefaultFlashBoxFactory;
use Framework\Html\FlashBox;
use Framework\Html\Form;

$formFactory=new UserFormFactory($router->generateURL('user',['action'=>'register']));
$inputFactory=new UserInputFactory();

$form=new Form($formFactory);
$form->setErrors($errors ?? []);

$form->addStyle('form');
$form->addInputWithLabel($inputFactory->text('email'), 'email')
     ->addInputWithLabel($inputFactory->text('username'), 'username')
     ->addInputWithLabel($inputFactory->password('password'), 'password')
     ->addInputWithLabel($inputFactory->password('passwordConfirmation'), 'password confirmation')
     ->addCancel('cancel', $router->generateURL('index'))
     ->addSubmit('Register');

$flashBox= new FlashBox(new DefaultFlashBoxFactory($session->flash()->getMessage()));

?>

<section class="block">
    <?= html_entity_decode($flashBox->toHtml()) ?>
    <?= $session->flash()->getMessage() ?>
    <h1 class="block__title block__title-center"> Registration </h1>
    <?= $form->toHtml() ;?>
</section>