<?php

use App\Identity\view\Factory\UserFormFactory;
use App\Identity\view\Factory\UserInputFactory;
use Framework\Html\Form;

$formFactory=new UserFormFactory($router->generateURL('user',['action'=>'signIn']));
$inputFactory=new UserInputFactory();

$form=new Form($formFactory);

$form->addStyle('form')
     ->addInputWithLabel($inputFactory->text('username'), 'username')
     ->addInputWithLabel($inputFactory->password('password'), 'password')
     ->addCancel('cancel', $router->generateURL('index'))
     ->addSubmit('Sign in');

$form->setErrors($errors ?? []);
$form->fillForm(isset($user) ? $user->toForm() : []);

var_dump($errors);
?>


<section class="block">
    <h1 class="block__title block__title-center"> Sign in </h1>
    <?= $form->toHtml() ;?>
</section