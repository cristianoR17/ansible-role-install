#!/usr/bin/php -q
<?php
ob_implicit_flush(true);
set_time_limit(6);
error_reporting(0); 
date_default_timezone_set('America/Fortaleza');
include 'db.php';

foreach($argv as $value){
  $id_user = $value;
}

$number=$argv[1];
$channel=$argv[2];
$login=$argv[3];

//echo "$number $channel \n";

$timestamp = time();
$img_file_name = "omnichannel/profile/user_with_out_img.jpg";

if (($number != "")) {

    $sql = "select id from chat_users where number='$number'";
    $results = $db->query($sql);

    while ($row = pg_fetch_assoc($results)) {
        $id = $row['id'];
    }


    //echo $id."\n";

    if ($id == "") {
        $sql = "insert into chat_users (name,photo,number,email,channel) values ('$number','$img_file_name','$number','$number',$channel)";
        $db->exec($sql);
    }

   $sql = "select id from chat_users where number='$number'";
    $results = $db->query($sql);

    while ($row = pg_fetch_assoc($results)) {
        $id_chat_user = $row['id'];
    }

    //echo $id_chat_user."\n";


    $sql = "select id from users where login='$login'";
    $results = $db->query($sql);

    while ($row = pg_fetch_assoc($results)) {
        $id_user = $row['id'];
    }

    //echo $id_user."\n";



    $sql = "insert into chat_list (id_user,enter_queue_timestamp,id_chat_user,channel) values ($id_user,$timestamp,$id_chat_user,$channel)";
    $db->exec($sql);

    $sql = "select id,join_chat from chat_list where end_timestamp is NULL and id_chat_user=$id_chat_user and enter_queue_timestamp=$timestamp";
    $results = $db->query($sql);

    while ($row = pg_fetch_assoc($results)) {
       $chat_list_id = $row['id'];
       $join_chat = $row['join_chat'];
    }


  //echo $chat_list_id."\n";

   $sql = "update chat_list set end_timestamp=$timestamp where id=$chat_list_id";
   $db->exec($sql);


   echo $chat_list_id."\n";








}

?>

