<?php 

include "../connect.php" ;

$email  = filterRequest("email") ; 

$verfiy = filterRequest("verifycode") ; 


$stmt = $con->prepare("SELECT * FROM users WHERE users_email = ? AND users_verfiycode = ? ") ; 
 
$stmt->execute([$email , $verfiy]) ; 

$count = $stmt->rowCount() ; 

 result($count) ;

?>