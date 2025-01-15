<?php
require('db_connect.php');
function uid_to_username($uid) {

    global $conn;
    
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
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
        $conn->close();
        return $row["username"];
    }
    else {
        $stmt->close();
        $conn->close();
        die("User with specified id doesn't exist in the database");
    }
}
?>