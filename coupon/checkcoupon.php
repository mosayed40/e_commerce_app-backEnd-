<?php 

include "../connect.php" ; 

$couponName = filterRequest("coupon_name") ; 

$now = date("Y-m-d H:i:s");

getData("coupon" , "coupon_name = ? AND coupon_expire_date > ? AND coupon_count > 0 " , [$couponName , $now]);

?>