<?php

include "../connect.php";

 
 $stmt = $con->prepare(
    
    "SELECT itemsview.* , 1 as favorite , (items_price - (items_price * items_discount / 100 ))  as itemspricedisount  FROM itemsview 
INNER JOIN favorite ON favorite.favorite_items_id = itemsview.items_id  
WHERE items_discount != 0
UNION ALL 
SELECT itemsview.*  , 0 as favorite  , (items_price - (items_price * items_discount / 100 ))  as itemspricedisount  FROM itemsview
WHERE  items_discount != 0  AND items_id NOT IN  (
SELECT itemsview.items_id FROM itemsview 
INNER JOIN favorite ON favorite.favorite_items_id = itemsview.items_id   )");

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count  = $stmt->rowCount();

if ($count > 0) {
    echo json_encode(array("status" => "success", "data" => $data));
} else {
    echo json_encode(array("status" => "failure"));
}

?>