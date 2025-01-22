<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    die("You have to log in to post");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["grade"]) || !isset($_POST["name"]) ||
    !isset($_POST["location"]) || !isset($_POST["description"]) ||
    !isset($_FILES["video"])) {

        die("Missing post parameters");
    }

    //validate input and insert it into the database
    if ($_POST["grade"] !== "" && strlen($_POST["grade"]) <= 15 &&
    strlen($_POST["name"]) <= 60 && $_POST["location"] !== "" &&
    strlen($_POST["location"]) <= 60 && strlen($_POST["description"]) <= 256) {

        //if a video file was attached, validate it, upload it to the server and send to db 

        require('post_data_insert.php');
        
        if ($_FILES["video"]["name"] !== "") {
            if ($_FILES["video"]["error"] !== 0) {
                die("There was en error uploading your file. Error code: ".$_FILES["video"]["error"]);
            }
            $video_file_type = strtolower(pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION));
            if (!in_array($video_file_type, ['mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv'])) {
                die("This video format is not supported. List of supported formats: mp4,avi,mov,mkv,flv,wmv");
            }
            if ($_FILES["video"]["size"] > 20971520) {
                die("Videos of size larger than 20MB aren't accepted");
            }
            
            $target_dir = "uploads/";
            //generate a unique filename for the video
            $new_file_id = uniqid();
            while (file_exists($target_dir.$new_file_id.".".$video_file_type)) {
                $new_file_id = uniqid();
            }
            $target_file = $target_dir.$new_file_id.".".$video_file_type;
            if(!move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
                die("Your file couldn't be moved to according server directory");
            }
        }
        insert_post_data($_SESSION["user_id"],$_POST["grade"],$_POST["name"],$_POST["location"],$_POST["description"],$target_file);
        header("Location: profile.php?id=".urlencode($_SESSION["user_id"]));
        exit();
    }
    else {
        die("Text data doesn't match the requested format");
    }
}
else {
    die("This page only supports post requests");
}
?>