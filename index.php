<?php
// Defining some vars to work with
$data = ['name' => '', 'email' => '', 'message' => ''];

// Main mail settings
file_exists('./secret.php')
? include_once "secret.php"
: define('EMAIL', 'your.email@example.com') && define('SUBJECT', 'Example subject of the mail');
    
if (isset($_POST['send']))
{
    // Vars
    $data = $_POST;

    // Super basic form validation, will write a validator class soon
    function validateForm($data)
    {
        $errors = array();
        // Regexes
        $regex_string = "/^[A-Za-z ]+$/";
        $regex_email = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
        // Rules
        if(empty($data['name'])) {
            $errors['name'] = 'This field is still empty?';
        }elseif(strlen($data['name']) <2) {
            $errors['name'] = 'Only 1 character?';
        }elseif(!preg_match($regex_string, $data['name'])) {
            $errors['name'] = 'Only alpha characters and spaces please';
        }
        if(empty($data['email'])) {
            $errors['email'] = 'This field is still empty?';
        }elseif(!preg_match($regex_email, $data['email'])) {
            $errors['email'] = 'This is not a valid email adress';
        }
        if(empty($data['message'])) {
            $errors['message'] = 'This field is still empty?';
        }elseif(strlen($data['message']) < 10) {
            $errors['message'] = 'At least 10 characters required.';
        }

        return $errors;     
    }

    function cleanString($string)
    {
        $danger = array("content-type","bcc:","to:","cc:","href");
        return str_replace($danger,"",$string);
    }

    // Check if all fields have content
    $errors = validateForm($data);
 
    if(empty($errors))
    {
        $email_message = "Name: ".cleanString($data['name'])."\n";
        $email_message .= "Email: ".cleanString($data['email'])."\n";
        $email_message .= "Message: ".cleanString($data['message'])."\n";
        $headers = 'From: '. EMAIL ."\r\n".'Reply-To: '. $data['email'] ."\r\n".'X-Mailer: PHP/' . phpversion();
        
        // Reset when succeeded
        $data = null;
        echo '<div class="part-contact__form__success">Thank You for contacting me</div>';
        
        //@mail(EMAIL, SUBJECT, $email_message, $headers);
    }   

}

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Portfolio Guy Pensart</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.min.css" rel="stylesheet">
        <!-- Temp bootstrap link to use the font awesome icons -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="css/pensart.css">

    </head>

    <body>
        <!-- Todo: clean and convert to pure HTML5 -->
        <section class="header">
            <h1 class="header__title">Guy Pensart</h1>
            <h2 class="sub-header" ><?php echo !empty($errors) ? 'Form failure' : 'Design &amp;&nbsp;Code'; ?></h2>
        </section>
        
        <?php if(empty($errors)): ?>
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
                <p class="part-social__item__copy">My playground where i experiment with front-end code.</p>
            </div>
            <div class="part-social__item">
                <a class="part-social__item__link" href="http://www.github.com/pensart" target="_blank">
                    <div class="part-social__item__icon--github"></div>
                </a>
                <h1>Github</h1>
                <p class="part-social__item__copy">Where i share full projects. Front and back-end code.</p>
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
                <?php if(!empty($errors['name'])): ?>
                    <div class="part-contact__form__error">
                        <?php echo $errors['name']; ?>
                    </div>
                <?php endif; ?>
                <input type="text" class="part-contact__form__input" name="name" placeholder="Name" value="<? echo $data['name']; ?>" >
                <?php if(!empty($errors['email'])): ?>
                    <div class="part-contact__form__error">
                        <?php echo $errors['email']; ?>
                    </div>
                <?php endif; ?>
                <input type="text" class="part-contact__form__input" name="email" placeholder="Email" value="<?php echo $data['email']; ?>">
                <?php if(!empty($errors['message'])): ?>
                    <div class="part-contact__form__error">
                        <?php echo $errors['message']; ?>
                    </div>
                <?php endif; ?>
                <textarea class="part-contact__form__textarea" name="message"><?php echo $data['message']; ?></textarea>
                <input type="submit" class="part-contact__form__submit" name="send" value="send message">
                
            </form>

            <div class="part-contact__bottom"></div>
        </section>
        <script src="js/pensart.js" type="text/javascript"></script>
    </body>

</html>