<?php

use App\Identity\view\Factory\UserFormFactory;
use App\Identity\view\Factory\UserInputFactory;
use App\Shared\Html\Factory\DefaultFlashBoxFactory;
use Framework\Html\Form;

$formFactory=new UserFormFactory($router->generateURL('user',['action'=>'register']));
$inputFactory=new UserInputFactory();
$form=new Form($formFactory);

$form->addStyle('form');
$form->addInputWithLabel($inputFactory->text('email'), 'email')
     ->addInputWithLabel($inputFactory->text('username'), 'username')
     ->addInputWithLabel($inputFactory->password('password'), 'password')
     ->addInputWithLabel($inputFactory->password('passwordConfirmation'), 'password confirmation')
     ->addCancel('cancel', $router->generateURL('index'))
     ->addSubmit('Register');

$form->setErrors($errors ?? []);
$form->fillForm(isset($user) ? $user->toForm() : []);
$flashBox= DefaultFlashBoxFactory::of($session->flash()); //new FlashBox(new DefaultFlashBoxFactory($session->flash()));

?>

<section class="block">
    <?= html_entity_decode($flashBox->toHtml()) ?>
    <?= $session->flash()->getMessage() ?>
    <h1 class="block__title block__title-center"> Registration </h1>
    <?= $form->toHtml() ;?>
</section


    