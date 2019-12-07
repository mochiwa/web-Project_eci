<?

use App\Shared\Html\LinkFactory;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title> John car | <?= $pageTitle ?? ' ' ?> </title>
        <meta name="description" content=" <?= $pageDescription ?? 'sightseeing the most beautiful parkings from belgium' ?>">
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/dist/app.css">
    </head>
    <div class="container container-main">
        <header class="container container-header">


            <section class="nav-top" id="mainTopNavBar">
                <div class="nav-top nav-top-left">
                    <div class="nav-logo">	
                        <h2><a class="nav-logo__item" href="">-LOGO-</a></h2>
                    </div>
                    <nav class="nav">
                        <?= LinkFactory::topNavLink($router->generateURL('home'), 'Home')->toHtml() ?>
                        <?= LinkFactory::topNavLink($router->generateURL('parking.home'), 'Parking')->toHtml() ?>
                        <?= LinkFactory::topNavLink($router->generateURL('contact'), 'Contact')->toHtml() ?>
                    </nav>
                </div>

                <div class="nav-top nav-top-right">
                    <nav class="nav">
                            <?= LinkFactory::topNavLink($router->generateURL('user.signIn'), 'Sign In')->toHtml() ?>
                            <?= LinkFactory::topNavLink($router->generateURL('user.signUp'), 'Sign Up', true)->toHtml() ?>
                    </nav>
                </div>
            </section>

            <section class="main-header">
                <div class="main-header-textArea">
                    <h2 class="main-header-textArea__title">John car</h2>
                    <p class="main-header-textArea__subtitle">sightseeing the most beautiful parking from Belgium</p>
                </div>
            </section>
        </header>
        <main class="container container-content">