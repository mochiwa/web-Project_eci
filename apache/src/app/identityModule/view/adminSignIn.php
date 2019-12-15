<?php

use App\Identity\view\Factory\UserFormFactory;
use App\Identity\view\Factory\UserInputFactory;
use App\Shared\Html\Factory\DefaultFlashBoxFactory;
use Framework\Html\Form;

$formFactory=new UserFormFactory($router->generateURL('admin.user',['action'=>'signIn']));
$inputFactory=new UserInputFactory();
$form=new Form($formFactory);

$form->addStyle('form')
     ->addInputWithLabel($inputFactory->text('username'), 'username')
     ->addInputWithLabel($inputFactory->password('password'), 'password')
     ->addCancel('cancel', $router->generateURL('index'))
     ->addSubmit('Sign in');

$form->setErrors($errors ?? []);
$form->fillForm(isset($admin) ? $admin->toForm() : []);
$flashBox= DefaultFlashBoxFactory::of($session->flash());

?>


<section class="block">
    <?= $flashBox->toHtml()?>
    <h1 class="block__title block__title-center"> Administrator Sign in </h1>
    <?= $form->toHtml() ;?>
</section>