<?php
    
    $form=new App\htmlBuilder\Form('CreateArticle',$router->generateURL('parking.create'),'POST');
    $form->addInput(App\htmlBuilder\Input::text('title', 'Type the parking title'))
            ->addInput(App\htmlBuilder\Input::file('picture','The picture'))
            ->addInput(App\htmlBuilder\Input::text('city', 'parking location'))
            ->addInput(App\htmlBuilder\Input::text('name', 'what is the name of the parking'))
            ->addInput(new Framework\HtmlBuilder\TextArea('description','The parking description'))
            ->addButton(App\htmlBuilder\Input::submit('submit', 'create'));
?>


<section class="block form">
    <h1 class="form__title">Create a parking</h1>
    <?= $form->toHtml() ?>
</section>