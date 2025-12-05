<?php

include "./connect.php";

$id = filterRequest("id");

$rating = filterRequest("rating");

$comment = filterRequest("comment");


$data = array(
    "orders_rating" =>  $rating,
    "orders_noterating" =>  $comment,
);

updateData("orders", $data, "orders_id = $id ");

?>