<?php 

include "../connect.php" ; 

$userid = filterRequest("user_id") ; 

getAllData('ordersview' , "orders_users_id = ? AND orders_status = 3 " , [$userid]) ; 

?>