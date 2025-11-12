<?php 

include "../connect.php" ; 


$usersid = filterRequest("users_id") ; 
$itemsid = filterRequest("items_id") ; 


$data = array(
    "favorite_users_id"  =>   $usersid , 
    "favorite_items_id"  =>   $itemsid
);


insertData("favorite" , $data) ; 

