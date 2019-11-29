<?php
    $attributes=$article->getAttributes();
    $form=new App\htmlBuilder\Form('CreateArticle',$router->generateURL('parking.admin.edit',['id'=>$article->getId()]),'POST');
    $form->addInput(App\htmlBuilder\Input::text('title', '',true, $article->getTitle()))
            ->addInput(App\htmlBuilder\Input::text('city', '',true,$attributes['city']))
            ->addInput(App\htmlBuilder\Input::text('place', 'Place count',true,$attributes['place']))
            ->addInput(App\htmlBuilder\Input::text('name', '',true,$attributes['name']))
            ->addInput(new Framework\HtmlBuilder\TextArea('description','',true,$article->getDescription()))
            ->addButton(App\htmlBuilder\Input::submit('submit','update'))
            ->addCancelButton($router->generateURL('parking.admin.index'));

    $form->setErrors($errors ?? []);
    
?>


<section class="block form">
    <h1 class="form__title">Create a parking</h1>
    <?= $form->toHtml() ?>
</section>