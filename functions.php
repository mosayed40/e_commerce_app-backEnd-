<?php

define("MB", 1048576);

function filterRequest($requestname)
{
    return isset($_POST[$requestname]) 
    ? htmlspecialchars(strip_tags($_POST[$requestname])) 
    : "";
}

function getAllData($table, $where = null, $values = null ,$json = true)
{
    global $con;
    $data = array();

    if ($where == null) {
     $stmt = $con->prepare("SELECT * FROM $table");
     $stmt->execute();
    } else {
     $stmt = $con->prepare("SELECT * FROM $table WHERE $where");
     $stmt->execute($values);
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();

   if ($json == true) {
        if ($count > 0) {
            echo json_encode(["status" => "success", "data" => $data]);
        } else {
            echo json_encode(["status" => "failure"]);
        }
        return $count;
    } else {
        if ($count > 0) {
            return  array("status" => "success", "data" => $data);
        } else {
             return  array("status" => "failure");
        }
    }
}

function getData($table, $where = null, $values = null , $json = true)
{
    global $con;
    $data = array();
    $stmt = $con->prepare("SELECT * FROM $table WHERE $where");
    $stmt->execute($values);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success", "data" => $data));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    } else {
        return $count;
    }
}

function insertData($table, $data, $json = true)
{
    global $con;
    $fields = implode(',', array_keys($data));
    $placeholders = implode(',', array_map(fn($key) => ':' . $key, array_keys($data)));
    $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
    $stmt = $con->prepare($sql);
    
    foreach ($data as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }

    $result = $stmt->execute();

    if ($json == true) {
        if ($result) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "failure"]);
        }
    }
    return $result;
}

function updateData($table, $data, $where, $json = true)
{
    global $con;
    $cols = array();
    $vals = array();

    foreach ($data as $key => $val) {
        $vals[] = "$val";
        $cols[] = "`$key` =  ? ";
    }
    $sql = "UPDATE $table SET " . implode(',', $cols) . " WHERE $where";

    $stmt = $con->prepare($sql);
    $stmt->execute($vals);
    $count = $stmt->rowCount();
    if ($json == true) {
    if ($count > 0) {
        echo json_encode(array("status" => "success"));
    } else {
        echo json_encode(array("status" => "failure"));
    }
    }
    return $count;
}

function deleteData($table, $where ,$values = [] ,  $json = true)
{
    global $con;
    $stmt = $con->prepare("DELETE FROM $table WHERE $where");

    if ($values != null) {
        $stmt->execute($values);
    } else {
        $stmt->execute();
    }
    $count = $stmt->rowCount();

    if ($json == true) {
        if ($count > 0) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    return $count;
}

function imageUpload($imageRequest)
{
  global $msgError;
  $imagename  = rand(1000, 10000) . $_FILES[$imageRequest]['name'];
  $imagetmp   = $_FILES[$imageRequest]['tmp_name'];
  $imagesize  = $_FILES[$imageRequest]['size'];
  $allowExt   = array("jpg", "png", "gif");
  $strToArray = explode(".", $imagename);
  $ext        = end($strToArray);
  $ext        = strtolower($ext);

  if (!empty($imagename) && !in_array($ext, $allowExt)) {
    $msgError = "EXT";
  }
  if ($imagesize > 2 * MB) {
    $msgError = "size";
  }
  if (empty($msgError)) {
    move_uploaded_file($imagetmp,  "../upload/" . $imagename);
    return $imagename;
  } else {
    return "fail";
  }
}

function deleteFile($dir, $imagename)
{
    if (file_exists($dir . "/" . $imagename)) {
        unlink($dir . "/" . $imagename);
    }
}

function   printSuccess($message = "success") 
{
    echo json_encode(["status" => "success" , "message" => $message]);
}

function   printFailure($message = "failure") 
{
    echo json_encode(["status" => "failure" , "message" => $message]);
}

function result($count)
{
   if ($count > 0){
    printSuccess() ; 
   }else {
    printFailure()  ; 
   }
   exit;
}

function sendEmail($to , $title , $body)
{
  $header = "From: support@moustafa.com " . "\n" . "CC: $to" ; 
  mail($to , $title , $body) ;  
}

function sendGCM($title, $message, $topic, $pageid, $pagename)
{


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
        "to" => '/topics/' . $topic,
        'priority' => 'high',
        'content_available' => true,

        'notification' => array(
            "title" =>  $title,
            "body" =>  $message,
            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "sound" => "default"

        ),
        'data' => array(
            "pageid" => $pageid,
            "pagename" => $pagename
        )

    );

    $fields = json_encode($fields);
    $headers = array(
        'Authorization: key=' . "BCSYCJD6pGOZ-Ik_-MS05FnWIhzRFzEZQE5s_6NFisWtUexV_LA-4Y26dEFBMO02LIE8JwGKELbm1GUNMLXlTU0",
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $result = curl_exec($ch);
    return $result;
    curl_close($ch);
}

function insertNotify($title, $body, $userid, $topic, $pageid, $pagename)
{
    global $con;
    $stmt  = $con->prepare("INSERT INTO `notification`(  `notification_title`, `notification_body`, `notification_userid`) VALUES (? , ? , ?)");
    $stmt->execute(array($title, $body, $userid));
    sendGCM($title,  $body, $topic, $pageid, $pagename);
    $count = $stmt->rowCount();
    return $count;
}

function checkAuthenticate()
{
    if (isset($_SERVER['PHP_AUTH_USER'])  && isset($_SERVER['PHP_AUTH_PW'])) {
        if ($_SERVER['PHP_AUTH_USER'] != "" ||  $_SERVER['PHP_AUTH_PW'] != "") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Page Not Found';
            exit;
        }
    } else {
        exit;
    }
}