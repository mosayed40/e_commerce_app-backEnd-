<?php 

include "../connect.php" ; 

$usersid = filterRequest("users_id") ; 

getAllData("address" , "address_users_id = ?" , [$usersid]) ; 