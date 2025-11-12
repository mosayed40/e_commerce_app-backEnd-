<?php 


include "../connect.php";

$usersid = filterRequest("users_id");
$itemsid = filterRequest("items_id");

$result = deleteData("cart", "cart_users_id = ? AND cart_items_id = ? AND cart_orders = 0", [$usersid, $itemsid] , false);

if ($result > 0) {
    echo json_encode([
        "status" => "success",
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to delete item or not found"
    ]);
}




