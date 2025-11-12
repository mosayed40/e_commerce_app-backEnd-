<?php

include "../connect.php";

$userid = filterRequest("users_id");

$data = getAllData("cartview", "cart_users_id = ?", [$userid] , false);

$stmt = $con->prepare(
  "SELECT  SUM(ask_price) AS totalprice, SUM(number_of_pieces) AS totalcount 
   FROM `cartview` 
   WHERE cartview.cart_users_id = :userid
   GROUP BY cart_users_id");

$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();

$datacountprice = $stmt->fetch(PDO::FETCH_ASSOC);

if ($datacountprice && !empty($data)) {

    echo json_encode([
        "status" => "success",
        "countprice" => $datacountprice,
        "data" => $data
    ]);

} else {

    echo json_encode([
        "status" => "empty",
        "message" => "Cart is empty",
        "data" => []
    ]);

}
