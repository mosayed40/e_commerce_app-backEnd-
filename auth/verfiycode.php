<?php 

include "../connect.php" ;

$email  = filterRequest("email") ; 

$verfiycode = filterRequest("verifycode") ; 

$stmt = $con->prepare("SELECT * FROM users WHERE users_email = ? AND users_verfiycode = ?");
$stmt->execute([$email, $verfiycode]);

$count = $stmt->rowCount() ; 

if ($count > 0) {
    
    $data = array("users_approve" => "1") ; 
    updateData("users" , $data , "users_email = '$email'" , false);
    echo json_encode(["status" => "success"]);

}else {
    echo json_encode([
        "status" => "failure",
        "message" => "verifycode not correct"
    ]);
}


