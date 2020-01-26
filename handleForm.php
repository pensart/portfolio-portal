<?php

include_once 'FormValidatorClass.php';
file_exists('./secret.php')
    ? include_once "secret.php"
    : define('EMAIL', 'your.email@example.com') && define('SUBJECT', 'Example subject of the mail') && define('FROM', 'no-spam@example.com');

$myObj = new stdClass();
$validate = new FormValidatorClass($_POST);
$rules = [
    "name" => [
        "min" => 20,
        "max" => 30
    ]
];

foreach ($_POST as $key => $value) {
    if(array_key_exists($key, $rules)) {
        $myObj->exists = 'exist';
    }
}

// if(array_key_exists('name', $_POST)) {
//     $item = "item";
//     $itemParam = 'name';
//     $methodName = "min";
//     $methodParam = true;
//     $validate->$item($itemParam);
//     $validate->$methodName($methodParam);
//     if($validate->errorsFree()) {
//         $myObj->name = $validate->getValue('name');
//     } else {
//         $myObj->name = $validate->getError('name');
//     } 
// }

// if($validate->errorsFree())
// {
//     $myObj->email = $validate->getValue('email');
//     $myObj->message = $validate->getValue('message');
// } else {
//     $myObj->validation = false;
// }


$myJSON = json_encode($myObj);
echo $myJSON;


?>