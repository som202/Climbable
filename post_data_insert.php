<?php
require('db_connect.php');
//function for inserting data into posts table
function insert_post_data($user_id,$grade,$name,$location,$description,$video_file) {

    global $conn;
    $stmt = $conn->prepare("INSERT INTO posts (user_id,grade,name,location,description,video_file) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isssss",$user_id,$grade,$name,$location,$description,$video_file);
    $t = func_get_args();
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        die("Database error: ".$stmt->error);
    }
    $stmt->close();
    $conn->close();
    
}