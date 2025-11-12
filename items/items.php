<?php

include "../connect.php";

$categoryid = filterRequest("id");     
$userid     = filterRequest("users_id");


if (empty($categoryid) || empty($userid)) {
    echo json_encode(array(
        "status"  => "failure",
        "message" => "Missing value in the form!",
    ));
    exit;
}

$stmt = $con->prepare(
    "SELECT itemsview.* , 1 as favorite , (itemsview.items_price - (itemsview.items_price * itemsview.items_discount / 100)) as itemsprice_discount FROM itemsview
 INNER JOIN favorite ON favorite.favorite_items_id = itemsview.items_id AND favorite.favorite_users_id = :userid
 WHERE categories_id = :catid
 UNION ALL
 SELECT itemsview.* , 0 as favorite ,  (itemsview.items_price - (itemsview.items_price * itemsview.items_discount / 100)) as itemsprice_discount FROM itemsview
 WHERE categories_id = :catid AND items_id NOT IN ( SELECT itemsview.items_id FROM itemsview
 INNER JOIN favorite ON favorite.favorite_items_id = itemsview.items_id AND favorite.favorite_users_id = :userid )"
 );

$stmt->execute(array(
    ":userid" => $userid,
    ":catid"  => $categoryid
));

$data  = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array(
        "status" => "success",
         "data" => $data, 
        ));
} else {
    echo json_encode(array("status" => "failure" ,  "message" => $e->getMessage()));
}


