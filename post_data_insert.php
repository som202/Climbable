<?php
require('db_connect.php');
//function for inserting data into posts table
function insert_post_data($user_id,$grade,$name,$location,$description,$video_file_path) {

    global $conn;
    $stmt = $conn->prepare("INSERT INTO posts (user_id,grade,name,location,description,video_file_path) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isssss",$user_id,$grade,$name,$location,$description,$video_file_path);
    $t = func_get_args();
    // foreach ($t as $key => $value) {
    //     echo $key." => ".$value;
    // }
    if(!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        die("Database error: ".$stmt->error);
    }
    $stmt->close();
    $conn->close();
    
}