<?php

/**
 * update: 20/09/2016 - form validation class
 * @author Guy Pensart
 * @link http://guypensart.be my personal site
 */

include 'FormValidatorClass.php';
file_exists('./secret.php')
    ? include_once "secret.php"
    : define('EMAIL', 'your.email@example.com') && define('SUBJECT', 'Example subject of the mail');

$placeholder =
    [
        'name'=>'Name',
        'email'=>'E-mail',
        'message'=>'Message'
    ];

if(empty($_POST)) {
    $validate = new FormValidatorClass(array());
} else {
    $validate = new FormValidatorClass($_POST);
    $validate
        ->item('name')->required('Name is required')->min(3)->max(40)->alphabet()->setValid()
        ->item('email')->required('E-mail is required')->min(8)->max(60)->email()->setValid()
        ->item('message')->required('Message is required')->min(22)->max(600)->text()->setValid();
        if($validate->errorsFree())
        {
            $emailMessage = 'Name: '.$validate->getValue('name')."\n";
            $emailMessage .= 'Email: '.$validate->getValue('email')."\n";
            $emailMessage .= 'Message: '.$validate->getValue('message')."\n";
            $headers = 'From: '. $validate->getValue('email') ."\r\n".'Reply-To: '. $validate->getValue('email') ."\r\n".'X-Mailer: PHP/' . phpversion();
            @mail(EMAIL, SUBJECT, $emailMessage, $headers);
            $validate->clearFields();
        }
}

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Portfolio Guy Pensart</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <meta name="author" content="Guy Pensart">
        <meta name="description" content="Guy Pensart portfolio portal">
        <meta name="keywords" content="guy, pensart, html, css, scss, jade, 3d, 2d, illustration, animation, coding, webdesign">
        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
        <link rel="icon" href="./favicon.ico" type="image/x-icon">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.min.css" rel="stylesheet">
        <!-- Temp bootstrap link to use the font awesome icons -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="css/pensart.css">

    </head>

    <body>
    <?php if($validate->errorsFree() && $validate->getValid('email')): ?>
            <div class="part-contact__form__success">Thank You for contacting me</div>
        <?php endif; ?>
        <!-- Todo: clean and convert to pure HTML5 -->
        <section class="header">
            <h1 class="header__title">Guy Pensart</h1>
            <h2 class="sub-header" ><?= !$validate->errorsFree() ? 'Form failure' : 'Design&nbsp;&amp;&nbsp;Code'; ?></h2>
        </section>
        
        <?php if($validate->errorsFree()): ?>
        <section class="part-social">
            <div class="part-social__top"></div>
            <div class="part-social__item">
                <a class="part-social__item__link" href="http://www.behance.com/pensart" target="_blank">
                    <div class="part-social__item__icon--behance js-attention"></div>
                </a>
                <h1>Behance</h1>
                <p class="part-social__item__copy">Discover my passion for webdesign, logo's and illustration.</p>
            </div>
            <div class="part-social__item">
                <a class="part-social__item__link" href="http://www.codepen.io/pensart" target="_blank">
                    <div class="part-social__item__icon--codepen"></div>
                </a>
                <h1>Codepen</h1>
                <p class="part-social__item__copy">My playground where I experiment with front-end code.</p>
            </div>
            <div class="part-social__item">
                <a class="part-social__item__link" href="http://www.github.com/pensart" target="_blank">
                    <div class="part-social__item__icon--github"></div>
                </a>
                <h1>Github</h1>
                <p class="part-social__item__copy">Where I share full projects. Front and back-end code.</p>
            </div>
            <div class="part-social__bottom"></div>
        </section>
        <header class="header-contact">
            <h2 class="sub-header">Contact</h2>
        </header>
        <?php endif; ?>
        <section class="part-contact">
            <div class="part-contact__top"></div>
            <form class="part-contact__form" method="post" enctype="application/x-www-form-urlencoded">
                <?php if(!$validate->errorsFree() && !$validate->getValid('name')): ?>
                    <div class="part-contact__form__error"><?= $validate->getError('name'); ?></div>
                <?php endif; ?>
                <input type="text" class="part-contact__form__input" name="name" placeholder=<?= '"'.$placeholder['name'].'"';?> value="<?= $validate->getValue('name'); ?>" >
                <?php if(!$validate->errorsFree() && !$validate->getValid('email')): ?>
                    <div class="part-contact__form__error"><?= $validate->getError('email'); ?></div>
                <?php endif; ?>
                <input type="text" class="part-contact__form__input" name="email" placeholder=<?= '"'.$placeholder['email'].'"';?> value="<?= $validate->getValue('email'); ?>">
                <?php if(!$validate->errorsFree() && !$validate->getValid('message')): ?>
                    <div class="part-contact__form__error"><?= $validate->getError('message'); ?></div>
                <?php endif; ?>
                <textarea class="part-contact__form__textarea" name="message" placeholder=<?= '"'.$placeholder['message'].'"';?>><?= $validate->getValue('message'); ?></textarea>
                <input type="submit" class="part-contact__form__submit" value="send message">
            </form>
            <div class="part-contact__bottom"></div>
        </section>
        <script src="js/pensart.js" type="text/javascript"></script>
    </body>

</html>