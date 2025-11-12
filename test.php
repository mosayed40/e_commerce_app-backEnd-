<?php

$notAuth = "" ;

include "./connect.php";

sendGCM("hi" , "How are you" , "" , "" , "") ;

echo "Not Auth" ;

?>