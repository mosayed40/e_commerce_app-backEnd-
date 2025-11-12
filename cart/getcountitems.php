<?php 

 include "../connect.php" ; 

 $usersid = filterRequest("users_id") ; 
 $itemsid = filterRequest("items_id") ; 

 $stmt = $con->prepare(
  "SELECT COUNT(cart.cart_id) 
   as totalcount 
   FROM `cart`
   WHERE cart_users_id = ?
   AND cart_items_id  = ? 
   AND cart_orders = 0
  ");

 $stmt->execute([$usersid,$itemsid]);

 $count = $stmt->rowCount() ; 

 $data = $stmt->fetchColumn() ; 
 

  if ($count > 0 ){
    
    echo json_encode(array("status" => "success" , "data" => $data)) ; 

  } else {

    echo json_encode(array("status" => "success" , "data" => "0")) ; 

  }

 

