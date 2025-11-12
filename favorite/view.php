<?php


include "../connect.php";


$id = filterRequest("id");


getAllData("myfavorite", "favorite_users_id = ?  ", array($id));

