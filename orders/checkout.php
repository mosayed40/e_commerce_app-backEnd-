<?php

include "../connect.php";

$usersid = filterRequest("users_id");
$addressid = filterRequest("address_id");
$pricedelivery = filterRequest("price_delivery");
$ordersprice = filterRequest("orders_price");
$couponid = filterRequest("coupon_id");
$paymentmethod = filterRequest("payment_method");
$coupondiscount = filterRequest("coupon_discount");



$totalprice = $ordersprice  + $pricedelivery;


// Check Coupon 

$now = date("Y-m-d H:i:s");

$checkcoupon = getData("coupon", "coupon_id = ? AND coupon_expire_date > ? AND coupon_count > 0  ", [$couponid , $now],  false);


if ($checkcoupon  > 0) {
    $totalprice =  $totalprice - $ordersprice * $coupondiscount / 100;
    $stmt = $con->prepare("UPDATE `coupon` SET  `coupon_count`= `coupon_count` - 1  WHERE coupon_id = '$couponid' ");
    $stmt->execute();
}


$data = array(
    "orders_users_id"  =>  $usersid,
    "orders_address"  =>  $addressid,
    "orders_price_delivery"  =>  $pricedelivery,
    "orders_price"  =>  $ordersprice,
    "orders_coupon"  =>  $couponid,
    "orders_total_price"  =>  $totalprice,
    "orders_payment_method"  =>  $paymentmethod
);

$count = insertData("orders", $data, false);

if ($count > 0) {

    $stmt = $con->prepare("SELECT MAX(orders_id) from orders ");
    $stmt->execute();
    $maxid = $stmt->fetchColumn();

    $data = array("cart_orders" => $maxid);

    updateData("cart", $data, "cart_users_id = $usersid  AND cart_orders = 0 ");
}