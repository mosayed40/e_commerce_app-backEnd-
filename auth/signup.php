<?php

include "../connect.php";

$username = filterRequest("username");
$email = filterRequest("email");
$phone = filterRequest("phone");
$password = sha1($_POST['password']);
$verfiycode  = rand(10000, 99999);

$stmt = $con->prepare("SELECT * FROM users WHERE users_email = ? OR users_phone = ? ");
$stmt->execute([$email, $phone]);
$count = $stmt->rowCount();
if ($count > 0) {
    printFailure("PHONE OR EMAIL");
} else {
    $data = array(
        "users_name" => $username,
        "users_email" => $email,
        "users_phone" => $phone,
        "users_password" =>  $password,
        "users_verfiycode" => $verfiycode,
    );
    // sendEmail($email , "verfiy Code Ecommerce" ,"Verfiy Code  $verfiycode" );
    insertData("users" , $data) ; 
}

