<?php

/**
 * @author Guy Pensart
 * @link http://guypensart.be my personal site
 */

include_once 'FormValidatorClass.php';
file_exists('./secret.php')
    ? include_once "secret.php"
    : define('EMAIL', 'your.email@example.com') && define('SUBJECT', 'Example subject of the mail') && define('FROM', 'no-spam@example.com');

$returnObj = new stdClass();
$validate = new FormValidatorClass($_POST);
$validate
->item('name')->required('Name is required')->min(3)->max(40)->alphabet()->setValid()
->item('email')->required('E-mail is required')->min(8)->max(60)->email()->setValid()
->item('message')->required('Message is required')->min(4)->max(600)->setValid();

if($validate->errorsFree() && MODE != 'development' && $_POST["single"] == "false")
{
    $emailMessage = 'Name: '.$validate->getValue('name')."\n";
    $emailMessage .= 'Email: '.$validate->getValue('email')."\n";
    $emailMessage .= 'Message: '.$validate->getValue('message')."\n";
    $headers = 'From: '. FROM ."\r\n".'Reply-To: '. $validate->getValue('email') ."\r\n".'X-Mailer: PHP/' . phpversion();
    @mail(EMAIL, SUBJECT, $emailMessage, $headers);
    $validate->clearFields();
    $returnObj->submitted = $validate->errorsFree();
}

if($validate->errorsFree() && MODE == 'development' && $_POST["single"] == "false") {
    $returnObj->submitted = $validate->errorsFree();
}

$returnObj->showSubmit = ($validate->errorsFree());

$filteredKeys = ["name", "email", "message"];

foreach (array_keys($_POST) as $key) {
    if(in_array($key, $filteredKeys)) {
        $returnObj->$key = [
            "value" => $validate->getValue($key),
            "error" => $validate->getError($key),
            "valid" => $validate->getValid($key)
        ];
    }
}

echo json_encode($returnObj);

?>