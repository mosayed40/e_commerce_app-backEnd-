<?php 

include "../connect.php" ; 

$addressid = filterRequest("address_id"); 

deleteData("address" , "address_id  = $addressid"); 