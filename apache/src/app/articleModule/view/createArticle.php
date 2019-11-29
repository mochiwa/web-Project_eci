<?php

use App\htmlBuilder\Form;
use App\htmlBuilder\Input;
use Framework\HtmlBuilder\Attribute;
use Framework\HtmlBuilder\TextArea;
    
    $form=new Form('CreateArticle',$router->generateURL('parking.admin.create'),'POST');
    $form->addInput(Input::text('title', 'Type the parking title'))
            ->addInput(Input::file('picture','The picture'))
            ->addInput(Input::text('city', 'parking location'))
            ->addInput(Input::text('place', 'parking location'))
            ->addInput(Input::text('name', 'what is the name of the parking'))
            ->addInput(new TextArea('description','The parking description'))
            ->addButton(Input::submit('submit', 'create'));
    
   $form->addAttribute((new Attribute('enctype'))->addContent('multipart/form-data'));

    $form->setErrors($errors ?? []);
    
    
?>


<section class="block form">
    <h1 class="form__title">Create a parking</h1>
    <?= $form->toHtml() ?>
</section>