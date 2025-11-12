<?php 

include "../connect.php" ; 

$usersid = filterRequest("users_id") ; 
$itemsid = filterRequest("items_id") ; 

deleteData("favorite" , "favorite_users_id = $usersid AND favorite_items_id = $itemsid") ; 

