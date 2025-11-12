<?php


include "../connect.php";

$usersid = filterRequest("users_id");
$itemsid = filterRequest("items_id");


$data = array(
    "cart_users_id" =>  $usersid,
    "cart_items_id" =>  $itemsid,
);

insertData("cart", $data);


