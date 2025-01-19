<?php
require('db_connect.php');
//$data parameter is column in users table in db
function uid_get_data($uid,$data) {
    if ($data !== "username" && $data !== "name" && $data !== "about" &&
        $data !== "is_public" && data !== "is_admin" && $data !== "picture_file") {
        die("Specified data column doesn't exist in db");
    }

    global $conn;
    $stmt = $conn->prepare("SELECT $data FROM users WHERE id = ?");
    $stmt->bind_param("i", $uid);
    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        die("Database error: ".$stmt->error);
    }
    
    $res = $stmt->get_result();
    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $stmt->close();
        //connection is gonna be closed in the main script, because this function is going to be called multiple times
        return $row[$data];
    }
    else {
        $stmt->close();
        $conn->close();
        die("User with specified id doesn't exist in the database");
    }
}
?>